<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2014 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Youtube extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $_xml_label;
	protected $_xml_thumbnail;
	protected $_xml_description;
	
	public function getProvider()
	{
		return 'youtube';
	}
	
	private function loadYoutubeData()
	{
		$url = 'http://gdata.youtube.com/feeds/api/videos/'.$this->video_value.'?v=2&alt=jsonc';

		$helper = Mage::helper('videogallery');
		$response = $helper->jsonDecode($helper->file_get_contents_curl($url));
		
		if (isset($response['data']['id']) && $response['data']['id'] = $this->video_value)
		{
			$this->_xml_label       = isset($response['data']['title'])       ? $response['data']['title']       : null;
			$this->_xml_description = isset($response['data']['description']) ? $response['data']['description'] : null;
			$this->_xml_thumbnail   = isset($response['data']['thumbnail']['hqDefault']) ? $response['data']['thumbnail']['hqDefault'] : null;
			return true;
		}
		
		return false;
	}
	
	
	public function setVideoByUrl( $url )
	{
		if (preg_match('#youtube.com#i' , $url))
		{
			if (!preg_match('#[\?!]v=([\w-]+)#' , $url , $matches ) )
			{
    			Mage::throwException(Mage::helper('videogallery')->__('Invalid Youtube URL. Video ID could not be found.'));
			}
			
			$this->video_value = isset($matches[1]) ? $matches[1] : null;
		}
		elseif (preg_match('#youtu.be#i' , $url))
		{
			if (!preg_match('#youtu.be/([\w-]+)#' , $url , $matches ) )
			{
    			Mage::throwException(Mage::helper('videogallery')->__('Invalid Youtube URL. Video ID could not be found.'));
			}
			
			$this->video_value = isset($matches[1]) ? $matches[1] : null;
		}
		else 
		{
			return false; // Not a valid youtube url
		}
		
		// Test if valid video id and load default data
		if (!$this->loadYoutubeData())
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Youtube Video ID.'));
		}
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		return $this->_xml_thumbnail;
	}
	
	public function getLabel()
	{
		return $this->_xml_label;
	}
	
	public function getDescription()
	{
		return $this->_xml_description;
	}
	

	public function getEmbedCode($width = 640, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width/1.6)-15;
		
		$autoplay = $autoplay ? 1 : 0;
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe id="ytplayer" type="text/html" width="{$width}" height="{$height}" src="//www.youtube.com/embed/{$this->video_value}?autoplay=$autoplay&rel=0&vq=hd720" frameborder="0">
</iframe>
</div>
END;
	}
	
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		$autoplay = $autoplay ? 1 : 0;
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe id="ytplayer" type="text/html" width="{$width}" height="{$height}" src="http://www.youtube.com/embed/{$this->video_value}?autoplay=$autoplay&rel=0" frameborder="0">
</iframe>
</div>
END;
	}
}
