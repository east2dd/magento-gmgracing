<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */

/**
 * Support for locally hosted videos using Flowplayer
 * March 2011
 */
class IcebergCommerce_VideoGallery_Model_Video_Localhost_Flowplayer extends IcebergCommerce_VideoGallery_Model_Video
{
	public function getProvider()
	{
		return 'localhost_flowplayer';
	}
	
	public function getDisplayName()
	{
		return 'Flowplayer';
	}
	
	public function setVideoByUrl($url)
	{
		$url = $this->cleanUrl($url);

		// Test url
		if (!$this->urlExists($this->getUrl($url)))
		{
			return false;
		}

		$this->video_value = $url;
		return $this;
	}
	
	
	public function getEmbedCode($width = 640, $height = null, $autoplay=true)
	{
		$height = ($height > 0) ? $height : floor( $width/1.6)-15;

		// This had to be done to get flowplayer to work right on no mobile devices and mobile
		// The flashplayer is superior to the html5 player.  so use the flash player, unless its a mobile device that does not run flash
		if ($this->isMobile())
		{
			return $this->getMobileEmbedCode($width, $height, $autoplay);
		}
		
		$autoplayConfig = $autoplay? '' : '"autoPlay":false, "autoBuffering":true,';
		
		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<object id="product_gallery_video" width="100%" height="100%">
		<param name="movie" value="{$this->getFlowplayerSrc()}" /> 
		<param name="bgcolor" value="#000000" />
		<param name="quality" value="high" />
		<param name="allowfullscreen" value="true" />
		<param name="flashvars" value='config={"clip":{"scaling":"fit", {$autoplayConfig} "url":"{$this->getUrl($this->video_value)}"},"canvas":{"backgroundColor":"#000000","backgroundGradient":"none"}}' />
		<embed src="{$this->getFlowplayerSrc()}"
			type="application/x-shockwave-flash" 
			wmode="transparent" 
		  	width="100%" height="100%"
			allowfullscreen="true"
			quality="high"
			bgcolor="#000000"
			flashvars='config={"clip":{"scaling":"fit", {$autoplayConfig} "url":"{$this->getUrl($this->video_value)}"},"canvas":{"backgroundColor":"#000000","backgroundGradient":"none"}}' />
		</embed>
	</object>
</div>

END;
	}
	
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		$autoplayConfig = $autoplay? 'autoplay' : '';

		return <<<END
<div class="video-object" style="width: {$width}px; height: {$height}px;">
	<video $autoplayConfig controls="controls" style="width:{$width}px;height:{$height}px; display: block; cursor: pointer; -webkit-user-drag: none; " src="{$this->getUrl($this->video_value)}"></video>
</div>
END;
	}
	
	/**
	 * If url is a url on the current domain, 
	 * clean off that part of the url so that it is easy to switch domains of store
	 */
	private function cleanUrl($url)
	{
		if (substr($url,0,7) != 'http://')
		{
			$url = 'http://' . $url;
		}
		
		$base = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		return str_replace($base,'',$url);
	}
	
	/**
	 * Returns full Url
	 * @param string $url
	 */
	private function getUrl($url)
	{
		if (substr($url,0,7) != 'http://')
		{
			$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $url;
		}
		
		return $url;
	}
	
	/**
	 * Get Flowplayer SWF
	 */
	private function getFlowplayerSrc()
	{
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS) . 'iceberg/videogallery/flowplayer.swf';
	}
	
	/**
	 * Override Parent Defn to add check for content length
	 * Check destination url to see if it is a valid video url
	 *
	 * @param unknown_type $url
	 * @return unknown
	 */
	protected function urlExists($url)
	{
		$info = array();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		// Only calling the head
		curl_setopt($ch, CURLOPT_HEADER, true); // header will be at output
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD'); // HTTP request is 'HEAD'
		curl_setopt($ch, CURLOPT_NOBODY, true);

		$ret = curl_exec($ch);

		if ($ret)
		{
			$info = curl_getinfo($ch);
		}

		curl_close($ch);

		$responseCode          = isset($info['http_code'])               ? $info['http_code']               : null;
		$downloadContentLength = isset($info['download_content_length']) ? $info['download_content_length'] : null;

		if ($responseCode >= 200 && $responseCode < 400 && $downloadContentLength > 0)
		{
			return true;
		}

		return false;
	}

}


