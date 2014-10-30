<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

class IcebergCommerce_VideoGallery_Block_Adminhtml_Gallery extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $html = $this->getContentHtml();
        //$html.= $this->getAfterElementHtml();
        return $html;
    }

    /**
     * Prepares content block
     *
     * @return string
     */
    public function getContentHtml()
    {
		// Get Video Attribute Group id so that we can hide the create attribute button
    	$setId = Mage::registry('product')->getAttributeSetId();
        $collection = Mage::getResourceModel('eav/entity_attribute_group_collection')->setAttributeSetFilter($setId);
        $videoGroupId = null;
        foreach ($collection as $group) 
        {
        	if ($group->getAttributeGroupName() == 'Videos')
        	{
        		$videoGroupId = $group->getId();
        	}
        }
        
        /* @var $content Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Gallery_Content */
        $content = Mage::getSingleton('core/layout')
            ->createBlock('videogallery/adminhtml_gallery_content');

        $content->setId($this->getHtmlId() . '_content')
            ->setElement($this)
            ->setGroupId($videoGroupId);
            
        return $content->toHtml();
    }

    public function getLabel()
    {
        return '';
    }

    /**
     * Check "Use default" checkbox display availability
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     */
    public function canDisplayUseDefault($attribute)
    {
        if (!$attribute->isScopeGlobal() && $this->getDataObject()->getStoreId()) {
            return true;
        }

        return false;
    }

    /**
     * Check default value usage fact
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     */
    public function usedDefault($attribute)
    {
        $devaultValue = $this->getDataObject()->getAttributeDefaultValue($attribute->getAttributeCode());
        return is_null($devaultValue);
    }

    /**
     * Retrieve data object related with form
     *
     * @return Mage_Catalog_Model_Product || Mage_Catalog_Model_Category
     */
    public function getDataObject()
    {
        return $this->getForm()->getDataObject();
    }

    /**
     * Retrieve attribute field name
     *
     *
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return string
     */
    public function getAttributeFieldName($attribute)
    {
        $name = $attribute->getAttributeCode();
        if ($suffix = $this->getForm()->getFieldNameSuffix()) {
            $name = $this->getForm()->addSuffixToName($name, $suffix);
        }
        return $name;
    }

    /**
     * Check readonly attribute
     *
     * @param Mage_Eav_Model_Entity_Attribute|string $attribute
     * @return boolean
     */
    public function getAttributeReadonly($attribute)
    {
        if (is_object($attribute)) {
            $attribute = $attribute->getAttributeCode();
        }

        if ($this->getDataObject()->isLockedAttribute($attribute)) {
            return true;
        }

        return false;
    }

    /**
     * Wrap final output in new row element.
     */
    public function toHtml()
    {
        return '<tr><td class="value" colspan="3">' . $this->getElementHtml() . '</td></tr>';
    }

}