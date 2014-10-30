<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2014 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Vine extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $_meta_title;
	protected $_meta_image;
	protected $_meta_description;
	
	public function getProvider()
	{
		return 'vine';
	}
	
	private function loadMetaData()
	{
		$url = "https://vine.co/v/{$this->video_value}";
    	$helper = Mage::helper('videogallery');
    	$data = $helper->file_get_contents_curl($url);
    
		preg_match('~<\s*meta\s+property="(twitter:description)"\s+content="([^"]*)~i', $data, $matches);
		if ( isset($matches[2]) ) 
		{
			$this->_meta_description = $matches[2];
		}
		
		preg_match('~<\s*meta\s+property="(twitter:title)"\s+content="([^"]*)~i', $data, $matches);
		if ( isset($matches[2]) ) 
		{
			$this->_meta_title = $matches[2];
		}
		
		preg_match('~<\s*meta\s+property="(twitter:image)"\s+content="([^"]*)~i', $data, $matches);
		if ( isset($matches[2]) ) 
		{
			$this->_meta_image = $matches[2];
		}
       
		return true;
	}
	
	
	public function setVideoByUrl( $url )
	{
		if (preg_match('#vine.co#i' , $url))
		{
			if (!preg_match('/^.*?vine\.co\/v\/(.*?)[\/]?$/' , $url , $matches ) )
			{
    			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vine URL. Video ID could not be found.'));
			}
			$this->video_value = isset($matches[1]) ? $matches[1] : null;
		}
		else 
		{
			return false; // Not a valid url
		}
		
		// Test if valid video id and load default data
		$this->loadMetaData();
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		return $this->_meta_image;
	}
	
	public function getLabel()
	{
		return $this->_meta_title;
	}
	
	public function getDescription()
	{
		return $this->_meta_description;
	}
	
	public function getEmbedCode($width = 600, $height = 600, $autoplay=true)
	{
		if ($width >= 600)
		{
			$width = 600;
			$height = 600;
		}
		
		if (!$height)
		{
			$height = $width;
		}
		
		$autoplayConfig = $autoplay ? 'audio=1' : '';
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<iframe class="vine-embed" src="//vine.co/v/{$this->video_value}/embed/simple?$autoplayConfig" width="{$width}" height="{$height}" frameborder="0"></iframe>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		return $this->getEmbedCode($width, $height);
	}
}
