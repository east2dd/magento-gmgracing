<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * Vzaar
 * October 2010
 * Only Pubic Videos have been Implemented
 */
class IcebergCommerce_VideoGallery_Model_Video_Vzaar extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $videoData = null;
	
	protected function getVideoData()
	{
		if (is_null($this->videoData)) 
		{
			try 
			{
				$apiUrl = "http://api.vzaar.com/videos/{$this->video_value}.json";
				$json = Mage::helper('videogallery')->file_get_contents_curl($apiUrl);
				$this->videoData = json_decode($json);
			}
			catch (Exception $e)
			{
				$this->videoData = new stdClass();
			}
		}
		
    	return $this->videoData;
	}
	
	public function getProvider()
	{
		return 'vzaar';
	}
	
	public function setVideoByUrl($url)
	{
		// Test if vzaar url
		if (!preg_match('#vzaar.com#i', $url))
		{
			return false;
		}
		
		// Test if valid format
		if (!preg_match('#vzaar.com/videos/(\d+)#', $url, $matches))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vzaar URL. Video ID could not be found.'));
		}
    	
		if (!isset($matches[1]))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vzaar URL. Video ID could not be found.'));
		}
    	
		$this->video_value = $matches[1];
    	
		
		// Test if valid video id
		if (!$this->getVideoData())
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Vzaar Video. Video does not exist.'));
		}
		
		return $this;
	}
	
	public function getThumbnail()
	{
		return $this->getVideoData()->framegrab_url;
	}
	
	public function getLabel()
	{
		return $this->getVideoData()->title;
	}
	
	public function getWidth()
	{
		return 640; // Use a default width
	}
	
	public function getHeight()
	{
		// scale based on 640 width and aspect ratio
		$width = $this->getVideoData()->width;
		$height = $this->getVideoData()->height;
		$aspect = $width/$height;
		
		return floor(640 * $height / $width);
	}
	
	public function getEmbedCode($width = 500, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : 370;
		$videoId = $this->video_value;
		
		$autoplayConfig = $autoplay ? 'autoplay=true' : '';

		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<object width="100%" height="100%" type="application/x-shockwave-flash" data="//view.vzaar.com/{$videoId}.flashplayer">
		<param name="movie" value="//view.vzaar.com/{$videoId}.flashplayer" />    
		<param name="allowScriptAccess" value="always" />
		<param name="allowFullScreen" value="true" />
		<param name="wmode" value="transparent" />
		<param name="flashvars" value="$autoplayConfig">
		<embed src="//view.vzaar.com/{$videoId}.flashplayer" type="application/x-shockwave-flash" wmode="transparent" width="{$width}" height="{$height}" allowScriptAccess="always" allowFullScreen="true" flashvars="$autoplayConfig"></embed>
		<video width="{$width}" height="{$height}" src="//view.vzaar.com/{$videoId}.mobile" poster="//view.vzaar.com/{$videoId}.image" controls></video>
	</object>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		$videoId = $this->video_value;
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<video width="{$width}" height="{$height}" src="http://view.vzaar.com/{$videoId}.mobile" poster="http://view.vzaar.com/{$videoId}.image" controls></video>
</div>
END;
	}
}

