<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_System_Config_Source_Displaystyle
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'iceberg/videogallery/media.phtml',                  		'label' => 'Style 01 - List with Lightbox'),
            array('value' => 'iceberg/videogallery/samples/media-tabbed1.phtml',  		'label' => 'Style 02 - Tabs with Lightbox'),
            array('value' => 'iceberg/videogallery/samples/media-tabbed2.phtml',  		'label' => 'Style 03 - Tabs'),
            array('value' => 'iceberg/videogallery/samples/media-images-videos.phtml',  'label' => 'Style 04 - Images + Videos Together'),
            array('value' => 'iceberg/videogallery/samples/media-custom.phtml',   		'label' => 'Style 05 - Customize Yourself'),
        );
    }
}