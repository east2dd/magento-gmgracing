<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * Add the video gallery attribute to the database
 * 
 * The video gallery attribute is an attribute part of every product similar to the media_gallery
 * it contains perstore information about video display. 
 * 
 */

$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('catalog_product_entity_media_video_gallery')}`
	CHANGE `value` `thumbnail` varchar(255) default NULL,
	CHANGE `video_id` `value` varchar(255) default NULL,
	MODIFY `provider` VARCHAR(50) default NULL
");


$installer->endSetup();