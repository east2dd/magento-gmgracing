<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */

/**
 * Helper
 * This helper class contains methods not used by video gallery core
 * The methods defined here have been developed for customizations
 */
class IcebergCommerce_VideoGallery_Helper_Extras extends Mage_Core_Helper_Abstract
{	
	
	/**
     * Load Video Gallery info into product
     * useful for product list page
     */
    public function loadGalleryIntoProduct($product)
    {
    	$attrCode = 'video_gallery';
        $value = array();
        $value['videos'] = array();
        $localAttributes = array('label', 'position', 'disabled','description');

        $videoGallery = Mage::getResourceSingleton('videogallery/backend_video')->loadGallery($product, null);
        
        foreach ($videoGallery as $video) 
        {
            foreach ($localAttributes as $localAttribute) 
            {
                if (is_null($video[$localAttribute])) 
                {
                    $video[$localAttribute] = isset($video[$localAttribute . '_default']) ? $video[$localAttribute . '_default'] : '';
                }
            }
            $value['videos'][] = $video;
        }
        $product->setData($attrCode, $value);
    }
    
    
    /**
     * Get a product's first video
     */
    public function getFirstProductVideo($product)
    {
    	$data = $product->getData();
    	if (!isset($data['video_gallery']))
    	{
			Mage::helper('videogallery/extras')->loadGalleryIntoProduct($product);
    	}
    	
    	$videos = $product->getData('video_gallery');
    	$videoCount = isset($videos['videos']) ? count($videos['videos']) : 0;
		
		if ($videoCount > 0)
		{
			foreach ($videos['videos'] as $v)
			{
				if (!$v['disabled'])
				{
					return $v;
				}
			}
		}
		
		return array();
	}
	
	
	/**
	 * Get a video url
     */
	public function getVideoUrl($product, $video)
	{
		if (isset($video['value']) && isset($video['value_id']) && isset($video['provider']))
		{
			$obj = Mage::helper('videogallery/video')->getVideoByValue($video['value'] , $video['provider']);
			if ($obj)
			{
				$params = array('product_id'=>$product->getId(), 'media_video_id' => $video['value_id']);
				return Mage::getUrl('videogallery/gallery/single/', $params);
			}
		}
		return null;
	}
	
	/**
	 * Get all embed codes
	 * returns array of html
	 */
	public function getAllVideoEmbeds($product)
	{
		$videos = $product->getData('video_gallery'); 
		$videoCount = isset($videos['videos']) ? count($videos['videos']) : 0;
		
		$arr = array();
		
		if ($videoCount > 0)
		{
			foreach ($videos['videos'] as $v)
			{
				if (!$v['disabled'])
				{
					$obj = Mage::helper('videogallery/video')->getVideoByValue($v['value'] , $v['provider']);
					if ($obj)
					{
						$arr[] = $obj->getEmbedCode(600, 400, $autoplay=false);
					}
				}
			}
		}
		return $arr;
	}
	
	
	/**
	 * Get all embed codes
	 * returns html
	 */
	public function getFirstVideoEmbed($product, $width, $height, $autoplay=false)
	{
		$videos = $product->getData('video_gallery'); 
		$videoCount = isset($videos['videos']) ? count($videos['videos']) : 0;
		
		$arr = array();
		
		if ($videoCount > 0)
		{
			foreach ($videos['videos'] as $v)
			{
				if (!$v['disabled'])
				{
					$obj = Mage::helper('videogallery/video')->getVideoByValue($v['value'] , $v['provider']);
					if ($obj)
					{
						return $obj->getEmbedCode($width, $height, $autoplay);
					}
				}
			}
		}
		return $arr;
	}
	
	
	
	public function getVideoThumb($product, $imgfile, $width_thumb, $height_thumb)
	{
		$marginLeft = 0;
		$marginTop  = 0;
		$imagehelper = $this->helper('catalog/image')->init($product, 'thumbnail', $imgfile);
		$imagehelper->__toString(); // Hack to load img dimensions
		$resize_width = $width_thumb;
    	$resize_height = $height_thumb;
    	$aspect = $width / $height;
    	$width  = $imagehelper->getOriginalWidth();
    	$height = $imagehelper->getOriginalHeight();
    	if ($width > $height)
    	{
    		$resize_width = $resize_height = ceil($resize_height * $width / $height);
    		$marginLeft = -1 * floor(($resize_width-$width_thumb)/2.0);
    	}
    	elseif ($width < $height)
    	{
    		$resize_width = $resize_height = ceil($resize_width * $height / $width);
    		$marginTop = -1 * floor(($resize_height-$height_thumb)/2.0);
    	}
		$_imageUrl = $imagehelper->keepAspectRatio(true)->constrainOnly(false)->keepFrame(false)->resize($resize_width,$resize_width);
		
		return $_imageUrl;
	}
	
}