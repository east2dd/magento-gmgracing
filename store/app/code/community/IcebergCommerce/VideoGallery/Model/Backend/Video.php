<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

class IcebergCommerce_VideoGallery_Model_Backend_Video
	extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
	//extends Mage_Catalog_Model_Product_Attribute_Backend_Media
{
    protected $_renamedVideos = array();

    /**
     * Load attribute data after product loaded
     *
     * @param Mage_Catalog_Model_Product $object
     */
    public function afterLoad($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = array();
        $value['videos'] = array();
        $value['values'] = array();
        $localAttributes = array('label', 'position', 'disabled','description');

        foreach ($this->_getResource()->loadGallery($object, $this) as $video) {
            foreach ($localAttributes as $localAttribute) {
                if (is_null($video[$localAttribute])) {
                    $video[$localAttribute] = $this->_getDefaultValue($localAttribute, $video);
                }
            }
            $value['videos'][] = $video;
        }
        $object->setData($attrCode, $value);
    }

    protected function _getDefaultValue($key, &$video)
    {
        if (isset($video[$key . '_default'])) {
            return $video[$key . '_default'];
        }

        return '';
    }

    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attrCode);

        if (!is_array($value)) {
            return;
        }
        if( !isset( $value['videos'] ) )
        {
        	$value['videos'] = array();
        }

        if(!is_array($value['videos']) && strlen($value['videos']) > 0) {
           $value['videos'] = Mage::helper('videogallery')->jsonDecode($value['videos']);
        }

        if (!is_array($value['videos'])) {
           $value['videos'] = array();
        }
        
        if( isset( $value['new_videos'] ) && is_array($value['new_videos'] ) )
        {
        	$value['videos'] = array_merge( $value['videos'] , $value['new_videos'] );
        }

        $clearVideos = array();
        $newVideos   = array();
        $existVideos = array();
        if ($object->getIsDuplicate()!=true) {
            foreach ($value['videos'] as &$video) {
                if(!empty($video['removed'])) {
                    $clearVideos[] = $video['value_id'];
                } else if (!isset( $video['value_id'] ) || !$video['value_id'] || $video['value_id'] == 'new' ) {
                	if( !isset($video['url']) || !$video['url'] )
                	{
                		$video['value_id']=null;
                		continue;
                	}
                	
                	$video['value_id'] = 'new';
                	
                	$videoRemote = $this->addVideo( $object , $video['url'] );
                    
                    // Now Merge the data.
                    if (isset($videoRemote['label']) && $videoRemote['label'] != '' && array_key_exists('label',$video) && trim($video['label'])=='')
                    {
                    	unset($video['label']);
                    }
                    
                    if (isset($videoRemote['position']) && $videoRemote['position'] != '' && array_key_exists('position',$video) && trim($video['position'])=='')
                    {
                    	unset($video['position']);
                    }
                    
                    $video = array_merge($videoRemote, $video);
                    
                    if (!$video['file'])
                    {
                    	unset( $video );
                    }
                    else 
                    {	
                    	$newVideos[$video['value_id']] = $video;
                    }
                } else {
                    
                	$videokey = 'custom_thumb_' . $video['value_id']; 
                    if (isset($_FILES) && isset($_FILES[$videokey])) 
                    {
                    	$tmp   = $_FILES[$videokey]['tmp_name'] ? $_FILES[$videokey]['tmp_name'] : null ;
                    	$name  = $_FILES[$videokey]['name']     ? $_FILES[$videokey]['name']     : null ;
                    	$mtype = $_FILES[$videokey]['type']     ? $_FILES[$videokey]['type']     : null ;
                    	
						$allowed_types = array( // List all allowed MIME Types here
							'image/gif',
							'image/jpeg',
							'image/tiff',
							'image/png',
							'image/tif',
						);
                    	
                    	if ($tmp)
                    	{
                    		if (!in_array($mtype,$allowed_types))
							{
								Mage::throwException(Mage::helper('videogallery')->__('Unsupported Image type specified - cannot upload image for video.') );
							}
						
                    		if (is_uploaded_file($tmp) && $filename = $this->uploadThumbnail($tmp, $name)) 
                    		{
                    			$video['file'] = $filename;
                    			$this->_getResource()->updateThumbnail( $video['value_id'] , $filename );
                    		}
                    	}
                    }
                    
                    $existVideos[$video['value_id']] = $video;
                }
            }
        } else {
            // For duplicating we need copy original videos.
            $duplicate = array();
            foreach ($value['videos'] as &$video) {
                if (!isset($video['value_id'])) {
                    continue;
                }
                $duplicate[$video['value_id']] = $this->_copyVideo($video['file']);
                $newVideos[$video['file']] = $duplicate[$video['value_id']];
            }

            $value['duplicate'] = $duplicate;
        }

        $object->setData($attrCode, $value);

        return $this;
    }

    public function afterSave($object)
    {
        if ($object->getIsDuplicate() == true) {
            $this->duplicate($object);
            return;
        }

        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attrCode);
        if (!is_array($value) || !isset($value['videos']) || $object->isLockedAttribute($attrCode)) {
            return;
        }
        
        $toDelete = array();
        $filesToValueIds = array();
        foreach ($value['videos'] as &$video) {
        	if( $video['value_id'] == null )
        	{
        		continue;
        	}
        	
            if(!empty($video['removed'])) {
                if(isset($video['value_id'])) {
                    $toDelete[] = $video['value_id'];
                }
                continue;
            }

            if($video['value_id'] == 'new') {
            	//New files need to be inserted into base gallery
                $data = array();
                $data['entity_id']      = $object->getId();
                $data['attribute_id']   = $this->getAttribute()->getId();
                $data['thumbnail']      = $video['file'];
                $data['provider'] 		= $video['provider'];
            	$data['value'] 		= $video['value'];
                $video['value_id']      = $this->_getResource()->insertGallery($data);
            }

            $this->_getResource()->deleteGalleryValueInStore($video['value_id'], $object->getStoreId());

            // Add per store labels, position, disabled
            $data = array();
            $data['value_id'] = $video['value_id'];
            $data['label']    = $video['label'];
            $data['description'] = $video['description'];
            //$data['provider'] = $video['provider'];	//we don't want to edit these once they've been set
            //$data['value'] = $video['value'];
            $data['position'] = (int) $video['position'];
            $data['disabled'] = isset( $video['disabled'] ) && !empty($video['disabled'] );
            $data['store_id'] = (int) $object->getStoreId();

            $this->_getResource()->insertGalleryValueInStore($data);
        }

        $this->_getResource()->deleteGallery($toDelete);
    }


    public function addVideo(Mage_Catalog_Model_Product $product, $url , $mediaAttribute=null, $move=false, $exclude=true)
    {
    	$video = Mage::helper( 'videogallery/video')->getVideoByUrl($url);
    	
    	if (!$video)
    	{
    		Mage::throwException(Mage::helper('videogallery')->__('Invalid URL. Unknown Provider.') );
    	}
    	
    	$value = $video->getVideoValue();
    	$provider = $video->getProvider();
    	$thumbnail = $video->getThumbnail();
    	$label = trim($video->getLabel());
    	$description = trim($video->getDescription());

    	// Getting ready to download and save thumbnail
    	$fileName = $this->uploadThumbnail( $thumbnail , $value . '.jpg' );

        $attrCode = $this->getAttribute()->getAttributeCode();
        $mediaGalleryData = $product->getData($attrCode);
        $position = 0;
        
        if (!is_array($mediaGalleryData )) {
            $mediaGalleryData = array(
                'videos' => array()
            );
        }
    	if(!is_array($mediaGalleryData['videos']) && strlen($mediaGalleryData['videos']) > 0) {
           $mediaGalleryData['videos'] = Mage::helper('videogallery')->jsonDecode($mediaGalleryData['videos']);
        }

        foreach ($mediaGalleryData['videos'] as &$video) {
            if (isset($video['position']) && $video['position'] > $position) {
                $position = $video['position'];
            }
        }

        $position++;
        
        $ret['file'] = $fileName;
        $ret['provider'] = $provider;
        $ret['value'] = $value;
        $ret['position'] = $position;
        $ret['label'] = $label;
        $ret['description'] = $description;
        
        return $ret;
    }
    
    public function uploadThumbnail($thumbnail, $correctFile = null)
    {
    	// If no thumbnail specified, use placeholder image
    	if (!$thumbnail) 
    	{
            // Check if placeholder defined in config
            $isConfigPlaceholder = Mage::getStoreConfig("catalog/placeholder/image_placeholder");
            $configPlaceholder   = '/placeholder/' . $isConfigPlaceholder;
            
            if ($isConfigPlaceholder && file_exists(Mage::getBaseDir('media') . '/catalog/product' . $configPlaceholder)) {
                $thumbnail = Mage::getBaseDir('media') . '/catalog/product' . $configPlaceholder;
            }
            else {
                // Replace file with skin or default skin placeholder
                $skinBaseDir     = Mage::getDesign()->getSkinBaseDir();
                $skinPlaceholder = "/images/catalog/product/placeholder/image.jpg";
                $thumbnail = $skinPlaceholder;
                if (file_exists($skinBaseDir . $thumbnail)) {
                    $baseDir = $skinBaseDir;
                }
                else {
                    $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default'));
                    if (!file_exists($baseDir . $thumbnail)) {
                        $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'base'));
                    }
                    if (!file_exists($baseDir . $thumbnail)) {
                        $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'default'));
                    }
                }
                $thumbnail = str_replace('adminhtml','frontend', $baseDir) . $thumbnail;
            }
    	}
    	
    	
    	if (!$correctFile)
    	{
    		$correctFile = basename( $thumbnail );
    	}
    	
    	$tempFile = null;
    	
    	if (Mage::helper('videogallery')->isUrl($thumbnail))
    	{
    		// Get Temporary location where we can save thumbnail to
			$adapter = new Varien_Io_File();
			$tempFile = $adapter->getCleanPath(Mage::getBaseDir('tmp')) . $correctFile;
			
			// Download to the temp location
			$thumbData = Mage::helper('videogallery')->file_get_contents_curl($thumbnail);
			@file_put_contents($tempFile, $thumbData);
			@chmod($tempFile, 0777);
			unset($thumbData);
			
			$thumbnail = $tempFile;
    	}
    	
    	// Getting ready to download and save thumbnail
        $fileName       = Varien_File_Uploader::getCorrectFileName( $correctFile );
        $dispretionPath = Varien_File_Uploader::getDispretionPath($fileName);
        //$fileName       = $dispretionPath . DS . $fileName;
        $fileName       = $dispretionPath . DS . Varien_File_Uploader::getNewFileName($this->_getConfig()->getMediaPath($fileName));

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->setAllowCreateFolders(true);
        $destinationDirectory = dirname($this->_getConfig()->getMediaPath($fileName));

        try {
            $ioAdapter->open(array(
                'path'=>$destinationDirectory
            ));

            if( !$ioAdapter->cp( $thumbnail , $this->_getConfig()->getMediaPath($fileName)) ){
                return false;
            }
            $ioAdapter->chmod($this->_getConfig()->getMediaPath($fileName), 0777);
            
            if ($tempFile)
            {
            	$ioAdapter->rm($tempFile);
            }
        }
        catch (Exception $e) {
            Mage::throwException(Mage::helper('videogallery')->__($e->getMessage()));
        }
        
        $fileName = str_replace(DS, '/', $fileName);
        
        return $fileName;
    }

    /**
     * Update video in gallery
     *
     * @param Mage_Catalog_Model_Product $product
     * @param sting $file
     * @param array $data
     * @return IcebergCommerce_VideoGallery_Model_Backend_Video
     */
    public function updateVideo(Mage_Catalog_Model_Product $product, $value_id, $data)
    {
        $fieldsMap = array(
        	'provier'  => 'provider',
        	'value' => 'value',
            'label'    => 'label',
            'position' => 'position',
            'disabled' => 'disabled',
            'exclude'  => 'disabled',
        	'description' => 'description'
        );

        $attrCode = $this->getAttribute()->getAttributeCode();

        $mediaGalleryData = $product->getData($attrCode);

        if (!isset($mediaGalleryData['videos']) || !is_array($mediaGalleryData['videos'])) {
            return $this;
        }

        foreach ($mediaGalleryData['videos'] as &$video) {
            if ($video['value_id'] == $value_id) {
                foreach ($fieldsMap as $mappedField=>$realField) {
                    if (isset($data[$mappedField])) {
                        $video[$realField] = $data[$mappedField];
                    }
                }
            }
        }

        $product->setData($attrCode, $mediaGalleryData);
        return $this;
    }

    /**
     * Remove video from gallery
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $file
     * @return IcebergCommerce_VideoGallery_Model_Backend_Video
     */
    public function removeVideo(Mage_Catalog_Model_Product $product, $value_id)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();

        $mediaGalleryData = $product->getData($attrCode);

        if (!isset($mediaGalleryData['videos']) || !is_array($mediaGalleryData['videos'])) {
            return $this;
        }

        foreach ($mediaGalleryData['videos'] as &$video) {
            if ($video['value_id'] == $value_id) {
                $video['removed'] = 1;
            }
        }

        $product->setData($attrCode, $mediaGalleryData);

        return $this;
    }

    /**
     * Retrive video from gallery
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $file
     * @return array|boolean
     */
    public function getVideo(Mage_Catalog_Model_Product $product, $value_id)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $mediaGalleryData = $product->getData($attrCode);
        if (!isset($mediaGalleryData['videos']) || !is_array($mediaGalleryData['videos'])) {
            return false;
        }

        foreach ($mediaGalleryData['videos'] as $video) {
            if ($video['value_id'] == $value_id) {
                return $video;
            }
        }

        return false;
    }

    /**
     * Clear media attribute value
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string|array $mediaAttribute
     * @return IcebergCommerce_VideoGallery_Model_Backend_Video
     */
    public function clearMediaAttribute(Mage_Catalog_Model_Product $product, $mediaAttribute)
    {
        $mediaAttributeCodes = array_keys($product->getMediaAttributes());

        if (is_array($mediaAttribute)) {
            foreach ($mediaAttribute as $atttribute) {
                if (in_array($atttribute, $mediaAttributeCodes)) {
                    $product->setData($atttribute, null);
                }
            }
        } elseif (in_array($mediaAttribute, $mediaAttributeCodes)) {
            $product->setData($mediaAttribute, null);

        }

        return $this;
    }

    /**
     * Set media attribute value
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string|array $mediaAttribute
     * @param string $value
     * @return IcebergCommerce_VideoGallery_Model_Backend_Video
     */
    public function setMediaAttribute(Mage_Catalog_Model_Product $product, $mediaAttribute, $value)
    {
        $mediaAttributeCodes = array_keys($product->getMediaAttributes());

        if (is_array($mediaAttribute)) {
            foreach ($mediaAttribute as $atttribute) {
                if (in_array($atttribute, $mediaAttributeCodes)) {
                    $product->setData($atttribute, $value);
                }
            }
        } elseif (in_array($mediaAttribute, $mediaAttributeCodes)) {
            $product->setData($mediaAttribute, $value);
        }

        return $this;
    }

    /**
     * Retrieve resource model
     *
     * @return IcebergCommerce_VideoGallery_Model_Resource_Mysql4_Backend_Video
     */
    protected function _getResource()
    {
        return Mage::getResourceSingleton('videogallery/backend_video');
    }

    /**
     * Retrive media config. We'll just reuse the images one... no need to duplicate.
     *
     * @return Mage_Catalog_Model_Product_Media_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('catalog/product_media_config');
    }


    /**
     * Copy video and return new filename.
     *
     * @param string $file
     * @return string
     */
    protected function _copyVideo($file)
    {
    	// Thumbs can just reference the same file path as original
    	return 1;
    	
        /*try {
            $ioObject = new Varien_Io_File();
            $destDirectory = dirname($this->_getConfig()->getMediaPath($file));
            $ioObject->open(array('path'=>$destDirectory));
            $destFile = dirname($file) . $ioObject->dirsep()
                      . Varien_File_Uploader::getNewFileName($this->_getConfig()->getMediaPath($file));

            if (!$ioObject->fileExists($this->_getConfig()->getMediaPath($file),true)) {
                throw new Exception();
            }
            $ioObject->cp(
                $this->_getConfig()->getMediaPath($file),
                $this->_getConfig()->getMediaPath($destFile)
            );
        } catch (Exception $e) {
            Mage::throwException(
                Mage::helper('catalog')->__('Failed to copy file %s. Please, delete media with non-existing videos and try again.',
                    $this->_getConfig()->getMediaPath($file))
            );
        }

        return str_replace($ioObject->dirsep(), '/', $destFile);*/
    }

    /**
     * Duplicate object data.
     * 
     * @param IcebergCommerce_VideoGallery_Model_Backend_Video $object
     * @return IcebergCommerce_VideoGallery_Model_Backend_Video
     */
    public function duplicate($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $mediaGalleryData = $object->getData($attrCode);

        if (!isset($mediaGalleryData['videos']) || !is_array($mediaGalleryData['videos'])) {
            return $this;
        }

        $this->_getResource()->duplicate(
            $this,
            (isset($mediaGalleryData['duplicate']) ? $mediaGalleryData['duplicate'] : array()),
            $object->getOriginalId(),
            $object->getId()
        );

        return $this;
    }
}