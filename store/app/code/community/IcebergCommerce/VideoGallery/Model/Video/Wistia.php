<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2014 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Wistia extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $_api_title;
	protected $_api_thumbnail;
	protected $_api_html;
	
	public function getProvider()
	{
		return 'wistia';
	}
	
	private function getVideoOEmbedData()
	{
		$url = "http://fast.wistia.com/oembed?url=http://home.wistia.com/medias/" . $this->video_value .'?embedType=seo';
		
		$helper = Mage::helper('videogallery');
		$response = $helper->jsonDecode($helper->file_get_contents_curl($url));
		
		
		if ($response && isset($response['title']) && isset($response['thumbnail_url']))
		{
			$this->_api_title     = $response['title'];
			$this->_api_thumbnail = $response['thumbnail_url'];
			$this->_api_html      = $response['html']; // Note - this should be stored in a better place
			return true;
		}
		
		return false;
	}
	
	
	public function setVideoByUrl( $url )
	{
		if (preg_match('#.wistia.#i' , $url))
		{
			$this->video_value = null;
			
			// ----------------------------------------------------
			// Supported formats:
			// http://fast.wistia.net/embed/iframe/XXX
			// http://*.wistia.com/medias/XXX
			// ----------------------------------------------------
			if (preg_match('#/embed/iframe/([\w-]+)#' , $url , $matches))
			{
				$this->video_value = isset($matches[1]) ? $matches[1] : null;
			}
			elseif (preg_match('#/medias/([\w-]+)#' , $url , $matches))
			{
				$this->video_value = isset($matches[1]) ? $matches[1] : null;
			}
			
			if (!$this->video_value)
			{
				Mage::throwException(Mage::helper('videogallery')->__('Invalid Wistia URL. Video ID could not be found.'));
			}
		}
		else 
		{
			return false; // Not a valid url
		}
		
		// Test if valid video id and load default data
		if (!$this->getVideoOEmbedData())
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Wistia Video ID.'));
		}
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		return $this->_api_thumbnail;
	}
	
	public function getLabel()
	{
		return $this->_api_title;
	}
	
	public function getDescription()
	{
		return $this->_api_html;
	}
	
	public function getEmbedCode($width = 640, $height = null, $autoplay=true)
	{
		$current_video = Mage::registry('current_video');
		
		if (!$current_video)
		{
			return $this->getMobileEmbedCode($width, $height, $autoplay);
		}
		
		$height = ($height > 0) ? $height : floor( $width/1.6)-15;
		$autoplayConfig = $autoplay ? 'true' : 'false';
		return $current_video['description']; // Description stores embed code for wistia (TODO - change this to another location where user cannot edit)
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width/1.6)-15;
		
		$autoplayConfig = $autoplay ? 'true' : 'false';

		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<iframe src="//fast.wistia.net/embed/iframe/{$this->video_value}?autoPlay=$autoplayConfig&controlsVisibleOnLoad=true&endVideoBehavior=reset&version=v1&videoHeight={$height}&videoWidth={$width}&volumeControl=true" 
		allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" width="{$width}" height="{$height}"></iframe>
</div>
END;
	}
}
