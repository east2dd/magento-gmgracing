<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Model_Video_Dailymotion extends IcebergCommerce_VideoGallery_Model_Video
{
	protected $fullVideoValue = null;
	
	public function getProvider()
	{
		return 'dailymotion';
	}
	
	public function setVideoByUrl( $url )
	{
		// Test if dailymotion url
		if (!preg_match('#dailymotion.com#i', $url))
		{
			return false;
		}
		
		// Test video format
		if (!preg_match('#dailymotion.com/video/(.*)#', $url, $matches))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Dailymotion URL. Video ID could not be found.'));
		}
		
		$this->fullVideoValue = isset($matches[1]) ? $matches[1] : null;
		
		$temp = explode('_',$this->fullVideoValue);
		
		/*if (!isset($temp[0]))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Dailymotion URL. Video ID could not be found.'));
		}*/
		
		$this->video_value = isset($temp[0]) ? $temp[0] : $matches[1];

		// Test if Valid Video Id
		if (!$this->urlExists($this->getThumbnail()))
		{
			Mage::throwException(Mage::helper('videogallery')->__('Invalid Dailymotion Video ID.'));
		}
		
		return $this;
	}
	
	public function getThumbnail()
	{
		return 'http://www.dailymotion.com/thumbnail/video/' . $this->video_value;
	}
	
	public function getLabel()
	{
		$url = 'https://api.dailymotion.com/video/' . $this->video_value;
		
		$helper = Mage::helper('videogallery');
		$hash = $helper->jsonDecode($helper->file_get_contents_curl($url));
		
		return isset($hash['title']) ? $hash['title'] : '';
	}
	
	public function getEmbedCode($width = 640, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width / 1.6)-15;
		
		$autoplayConfig = $autoplay ? '&autoPlay=1' : '';

		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe frameborder="0" width="{$width}" height="{$height}" src="//www.dailymotion.com/embed/video/{$this->video_value}?width=&theme=none&foreground=%23F7FFFD&highlight=%23FFC300&background=%23171D1B&additionalInfos=1&related=0&start=&animatedTitle=&iframe=0&hideInfos=0&wmode=transparent{$autoplayConfig}"></iframe>
</div>
END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		
		if ($height < 200)
		{
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<div style="height:{$height}px;overflow:hidden">
<iframe style="margin-top:-75px;width:{$width}px;height:200px;overflow:hidden;" frameborder="0" width="{$width}" height="200" src="http://www.dailymotion.com/embed/video/{$this->video_value}"></iframe>
</div>
</div>
END;
		}
		else 
		{
			return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
<iframe style="width:{$width}px;height:{$height}px;overflow:hidden;" frameborder="0" width="{$width}" height="{$height}" src="http://www.dailymotion.com/embed/video/{$this->video_value}"></iframe>
</div>
END;
		}

	}

}