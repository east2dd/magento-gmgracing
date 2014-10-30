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

// Load all Attribute Sets
$attributeSetIds = $installer->getAllAttributeSetIds($installer->getEntityTypeId('catalog_product'));

// Add "Videos" attribute group to all Attribute Sets
foreach ($attributeSetIds as $id)
{
	$installer->addAttributeGroup('catalog_product', $id, 'Videos', 4);
}

// Create new attribute "video_gallery"
$installer->addAttribute('catalog_product', 'video_gallery', array(
		'group'			=> 'Videos',
        'backend'       => 'videogallery/backend_video',
		'input_renderer'=> 'videogallery/adminhtml_gallery',
        'frontend'      => '',
        'label'         => 'Video Gallery',
        'input'         => 'videogallery',
        'type'          => 'varchar',
        'class'         => '',
        'source'        => '',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => true,
        'default'       => '',
        'searchable'    => false,
        'filterable'    => false,
        'unique'        => false,
        'comparable'    => false,
        'visible_on_front'  => false,
));

// Add new "video_gallery" attribute to the "Videos" attribute group in each attribute set
foreach ($attributeSetIds as $id)
{
	$installer->addAttributeToSet('catalog_product', $id, 'Videos', 'video_gallery');  
}

$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('catalog_product_entity_media_video_gallery')}`;
CREATE TABLE `{$installer->getTable('catalog_product_entity_media_video_gallery')}` (
  `value_id` int(11) unsigned NOT NULL auto_increment,
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `thumbnail` varchar(255) default NULL,
  `value` varchar(255) default NULL,
  `provider` varchar(50) default NULL,
  PRIMARY KEY  (`value_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_ATTRIBUTE` (`attribute_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_ENTITY` (`entity_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog product media VIDEO gallery';

DROP TABLE IF EXISTS `{$installer->getTable('catalog_product_entity_media_video_gallery_value')}`;
CREATE TABLE `{$installer->getTable('catalog_product_entity_media_video_gallery_value')}` (
  `value_id` int(11) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `label` varchar(255) default NULL,
  `position` int(11) unsigned default NULL,
  `disabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`value_id`,`store_id`),
  KEY `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_VALUE_STORE` (`store_id`),
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_VALUE_GALLERY` FOREIGN KEY (`value_id`) REFERENCES `{$installer->getTable('catalog_product_entity_media_video_gallery')}` (`value_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CATALOG_PRODUCT_MEDIA_VIDEO_GALLERY_VALUE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$installer->getTable('core_store')}` (`store_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalog product media VIDEO gallery values';

");

$installer->endSetup();
