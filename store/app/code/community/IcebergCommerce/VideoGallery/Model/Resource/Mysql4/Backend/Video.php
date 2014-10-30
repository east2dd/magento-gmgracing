<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

class IcebergCommerce_VideoGallery_Model_Resource_Mysql4_Backend_Video extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
{
	/**
     * Gallery db tables
     */
    const GALLERY_TABLE = 'videogallery/attribute_gallery';
    const GALLERY_VALUE_TABLE = 'videogallery/attribute_gallery_value';
    
    /** Does not exist... Silly Magento Crew
    * const GALLERY_IMAGE_TABLE = 'catalog/product_attribute_media_gallery_image';
    */
    
	protected function _construct()
    {
        $this->_init(self::GALLERY_TABLE, 'value_id');
    }
	
    /**
     * Load video gallery for product
     *
     * @param Mage_Catalog_Model_Product $product
     * @param IcebergCommerce_VideoGallery_Model_Backend_Video $object
     * @return array
     */
    public function loadGallery($product, $object)
    {
    	$attribute = $object ? $object->getAttribute() : Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product', 'video_gallery');
    	
        // Select gallery images for product
        $select = $this->_getReadAdapter()->select()
            ->from(
                array('main'=>$this->getTable(self::GALLERY_TABLE)),
                array('value_id', 'thumbnail AS file', 'value', 'provider' )
            )
            ->joinLeft(
                array('value'=>$this->getTable(self::GALLERY_VALUE_TABLE)),
                'main.value_id=value.value_id AND value.store_id='.(int)$product->getStoreId(),
                array('label','position','disabled','description')
            )
            ->joinLeft( // Joining default values
                array('default_value'=>$this->getTable(self::GALLERY_VALUE_TABLE)),
                'main.value_id=default_value.value_id AND default_value.store_id=0',
                array(
                    'label_default' => 'label',
                    'position_default' => 'position',
                    'disabled_default' => 'disabled',
                	'description_default' => 'description'
                )
            )
            ->where('main.attribute_id = ?', $attribute->getId())
            ->where('main.entity_id = ?', $product->getId())
            ->order('IF(value.position IS NULL, default_value.position, value.position) ASC');

        $result = $this->_getReadAdapter()->fetchAll($select);
        $this->_removeDuplicates($result);
        return $result;
    }
    
    public function getMainTable(){
    	return $this->getTable( self::GALLERY_TABLE );
    }
	/**
     * Insert gallery value for store to db
     *
     * @param array $data
     * @return IcebergCommerce_VideoGallery_Model_Resource_Mysql4_Backend_Video
     */
    public function insertGalleryValueInStore($data)
    {
        $this->_getWriteAdapter()->insert($this->getTable(self::GALLERY_VALUE_TABLE), $data);
        return $this;
    }

    /**
     * Delete gallery value for store in db
     *
     * @param integer $valueId
     * @param integer $storeId
     * @return IcebergCommerce_VideoGallery_Model_Resource_Mysql4_Backend_Video
     */
    public function deleteGalleryValueInStore($valueId, $storeId)
    {
        $this->_getWriteAdapter()->delete(
                $this->getTable(self::GALLERY_VALUE_TABLE),
                'value_id = ' . (int)$valueId  . ' AND store_id = ' . (int)$storeId
        );

        return $this;
    }
    
    public function updateThumbnail( $valueId , $thumbnail )
    {
    	$this->_getWriteAdapter()->update($this->getMainTable(),
                array('thumbnail' => $thumbnail ),
                array(
                    $this->_getWriteAdapter()->quoteInto('value_id=?', $valueId),
                )
            );
            
        return $this;
    }
    
	/**
     * Duplicates gallery db values
     *
     * @param IcebergCommerce_VideoGallery_Model_Backend_Video $object
     * @param array $newFiles
     * @param int $originalProductId
     * @param int $newProductId
     * @return IcebergCommerce_VideoGallery_Model_Resource_Mysql4_Backend_Video
     */
    public function duplicate($object, $newFiles, $originalProductId, $newProductId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), array('value_id', 'value' , 'provider' , 'thumbnail'))
            ->where('attribute_id = ?', $object->getAttribute()->getId())
            ->where('entity_id = ?', $originalProductId);

        $valueIdMap = array();
        // Duplicate main entries of gallery
        foreach ($this->_getReadAdapter()->fetchAll($select) as $row) {
            $data = array(
                'attribute_id' => $object->getAttribute()->getId(),
                'entity_id'    => $newProductId,
            	'provider'	   => (isset($newFiles[$row['provider']]) ? $newFiles[$row['provider']] : $row['provider']),
            	'thumbnail'	   => (isset($newFiles[$row['thumbnail']]) ? $newFiles[$row['thumbnail']] : $row['thumbnail']),
                'value'        => (isset($newFiles[$row['value']]) ? $newFiles[$row['value']] : $row['value'])
            );

            $valueIdMap[$row['value_id']] = $this->insertGallery($data);
        }

        if (count($valueIdMap) == 0) {
            return $this;
        }

        // Duplicate per store gallery values
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable(self::GALLERY_VALUE_TABLE))
            ->where('value_id IN(?)', array_keys($valueIdMap));

        foreach ($this->_getReadAdapter()->fetchAll($select) as $row) {
            $row['value_id'] = $valueIdMap[$row['value_id']];
            $this->insertGalleryValueInStore($row);
        }

        return $this;
    }
}
