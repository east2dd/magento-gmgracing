<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * Observer model
 */
class IcebergCommerce_VideoGallery_Model_Observer
{	
	/**
	 * Hook into product attribute mass edit page
	 * Disable Video Gallery attributes from that edit page
	 * 
	 * @param object $observer
	 */
	public function adminhtml_catalog_product_form_prepare_excluded_field_list($observer)
	{
		$object = $observer->getObject();
		
		$list = array_merge($object->getFormExcludedFieldList(),array('video_gallery'));
		$object->setFormExcludedFieldList($list);
	}
}


