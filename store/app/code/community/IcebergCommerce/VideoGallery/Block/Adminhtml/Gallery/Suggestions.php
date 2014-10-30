<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * For Product Edit Page Video Tab
 */
class IcebergCommerce_VideoGallery_Block_Adminhtml_Gallery_Suggestions extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        
        // Set template for content block.
        $this->setTemplate('iceberg/videogallery/suggestions.phtml');
    }
}