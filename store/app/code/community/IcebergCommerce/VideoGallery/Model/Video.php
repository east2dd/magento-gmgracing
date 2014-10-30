<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */
abstract class IcebergCommerce_VideoGallery_Model_Video extends Mage_Core_Model_Abstract
{
	public $video_value;
	
	/**
	 * @return string
	 */
	public function getVideoValue()
	{
		return $this->video_value;
	}
	
	/**
	 * @return string
	 */
	public function getProvider()
	{
		return 'undefined';
	}
	
	/**
	 * @return string
	 */
	public function getDisplayName()
	{
		return ucfirst($this->getProvider());
	}
	
	/**
	 * Initialize video based on URL.
	 * Returns false if url is not applicable url.
	 *  throw exception if url malformed.
	 * 
	 * @param string $url
	 * @return IcebergCommerce_VideoGallery_Model_Video|bool
	 */
	abstract public function setVideoByUrl( $url );
	
	/**
	 * Set video using video ID
	 * @param unknown_type $id
	 */
	public function setVideoByValue( $value )
	{
		$this->video_value = $value;
		
		return $this;
	}
	
	/**
	 * Get Thumbnail provided by video provider.
	 */
	public function getThumbnail()
	{	
		return '';
	}
	
	/**
	 * Return label provided by video Provider
	 */
	public function getLabel()
	{
		return '';
	}
	
	/**
	 * Return description provided by video Provider
	 */
	public function getDescription()
	{
		return '';
	}
	
	/**
	 * Return embed HTML Code
	 */
	abstract public function getEmbedCode($width = 640, $height=null, $autoplay=true);
	
	/**
	 * Return embed HTML Code Specifically for mobile devices
	 */
	public function getMobileEmbedCode($width, $height, $autoplay=true)
	{
		return $this->getEmbedCode($width, $height);
	}
	
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
		//$downloadContentLength = isset($info['download_content_length']) ? $info['download_content_length'] : null;

		if ($responseCode >= 200 && $responseCode < 400)
		{
			return true;
		}

		return false;
	}
	
	public function isMobile()
    {
    	return Mage::helper('videogallery')->isMobile();
    }
    
}