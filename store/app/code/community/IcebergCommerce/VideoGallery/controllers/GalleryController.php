<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_GalleryController extends Mage_Core_Controller_Front_Action
{
    /**
     * Display Video
     */
    public function singleAction()
    {
    	$width  = (int) $this->getRequest()->getParam('width',  null);
    	$height = (int) $this->getRequest()->getParam('height', null);
    	
    	if (!$width)
    	{
    		$defaultWidth = Mage::helper('videogallery')->getDefaultVideoWidth();
    		$width = $defaultWidth > 0 ? $defaultWidth : 650;
    	}
    	
    	if (!$height)
    	{
    		$defaultHeight = Mage::helper('videogallery')->getDefaultVideoHeight();
    		$height = $defaultHeight > 0 ? $defaultHeight : floor( $width/1.6);
    	}
    	
    	
    	// Set default sizes for gallery videos
		$data = array(
			'media_video_id' => (int) $this->getRequest()->getParam('media_video_id'),
			'product_id'     => (int) $this->getRequest()->getParam('product_id'),
			'width'          => $width,
			'height'         => $height, 
		);
		
		$block = $this->getLayout()->createBlock('videogallery/single')->setData($data);
		$this->getResponse()->setBody( $block->toHtml() );
    }
}
