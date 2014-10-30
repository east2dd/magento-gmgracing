<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Smugmug extends IcebergCommerce_VideoGallery_Model_Video
{
	public function getProvider()
	{
		return 'smugmug';
	}
	
	public function setVideoByUrl( $url )
	{
		// Test if valid url
		// http://user.smugmug.com/photos/video.mp4
		if ( !preg_match( '#smugmug.com#i' , $url ) )
		{
			return false;
		}
		
		// Test format of url	
		$id = null;
		$parts = explode("/", $url);
		$len = count($parts);
		if ($len > 0)
		{
			$last = $parts[$len-1];
			if (stristr($last,'_'))
			{
				// clean up id
				$id = $last;
				$temp = explode('#', $id); $id = $temp[0];
				$temp = explode('-', $id); $id = $temp[0];
				$temp = explode('.', $id); $id = $temp[0];
			}
		}
		
		
		if ($id == null)
		{
    		Mage::throwException(Mage::helper('videogallery')->__('Invalid SmugMug URL. Video ID could not be found. URL should be in format: "http://user.smugmug.com/photos/video.mp4"'));
		}

		$this->video_value = $id;
		    	
		// Test if valid video id
		if (!$this->urlExists($this->getThumbnail()))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid SmugMug Video ID (could not verify by downloading thumb).'));
		}
    	
		return $this;
	}
	
	public function getThumbnail()
	{
		return 'http://smugmug.com/photos/'. $this->video_value .'-S.jpg';
	}
	
	public function getEmbedCode($width = 640, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width/1.6)-15;
		
		$autoplayConfig = $autoplay ? '&autoplay=true' : '';
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<iframe frameborder="0" scrolling="no" width="{$width}" height="{$height}" src="//api.smugmug.com/services/embed/{$this->video_value}?width={$width}&height={$height}&hd=true{$autoplayConfig}"></iframe>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<iframe frameborder="0" scrolling="no" width="{$width}" height="{$height}" src="http://api.smugmug.com/services/embed/{$this->video_value}?width={$width}&height={$height}&hd=true{$autoplayConfig}"></iframe>
</div>
END;
	}
}
