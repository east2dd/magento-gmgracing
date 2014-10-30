<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2014 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Instagram extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $_api_title;
	
	public function getProvider()
	{
		return 'instagram';
	}
	
	
	private function loadOembedData()
	{
		$url = "http://api.instagram.com/oembed?url=http://instagr.am/p/" . $this->video_value;
		
		$helper = Mage::helper('videogallery');
		$response = $helper->jsonDecode($helper->file_get_contents_curl($url));
		
		if ($response && isset($response['title']))
		{
			$this->_api_title = $response['title'];
			return true;
		}
		
		return false;
	}
	
	
	public function setVideoByUrl( $url )
	{
		if (preg_match('#instagram.com#i' , $url))
		{
			if (!preg_match('/^.*?instagram\.com\/p\/(.*?)[\/]?$/' , $url , $matches ) )
			{
    			Mage::throwException(Mage::helper('videogallery')->__('Invalid Instagram URL. Post ID could not be found.'));
			}
			
			$this->video_value = isset($matches[1]) ? $matches[1] : null;
		}
		else 
		{
			return false; // Not a valid url
		}
		
		if (!$this->loadOembedData())
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Instagram Video ID.'));
		}
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		return "http://instagr.am/p/{$this->video_value}/media/?size=l";
	}
	
	public function getLabel()
	{
		return $this->_api_title;
	}
	
	public function getDescription()
	{
		return $this->_api_title;
	}
	
	public function getEmbedCode($width = 612, $height = 710, $autoplay=true)
	{
		if ($width>=612)
		{
			$width = 612;
			$height = 710;
		}
		elseif ($height)
		{
			//$width = floor($height * 0.86);
		}
		else 
		{
			$height = floor( $width/0.86);
		}
		
		//$autoplayConfig = $autoplay ? '&autoplay=1' : '';
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe src="//instagram.com/p/{$this->video_value}/embed/" width="{$width}" height="{$height}" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		return $this->getEmbedCode($width, $height, $autoplay);
	}
}
