<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * Helper
 */
class IcebergCommerce_VideoGallery_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Make Video Gallery 1.3.x compatible
	 * Copied from Mage_Core_Helper_Data
	 */
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array())
    {
    	$helper = Mage::helper('core');
    	$json = null;
    	
    	if (method_exists($helper,'jsonEncode'))
    	{
    		$json = $helper->jsonEncode($valueToEncode, $cycleCheck, $options);
    	}
    	else
    	{
    		$json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
    	}
    	
        return $json;
    }

    /**
     * Make Video Gallery 1.3.x compatible
	 * Copied from Mage_Core_Helper_Data
     */
    public function jsonDecode($encodedValue, $objectDecodeType = Zend_Json::TYPE_ARRAY)
    {
    	$helper = Mage::helper('core');
    	$json = null;
    	
    	if (method_exists($helper,'jsonDecode'))
    	{
    		$json = $helper->jsonDecode($encodedValue, $objectDecodeType);
    	}
    	else
    	{
        	$json = Zend_Json::decode($encodedValue, $objectDecodeType);
    	}
    	
    	return $json;
    }
    
    public function isVideoSuggestionsEnabled()
    {
    	return Mage::getStoreConfig('videogallery/adminconfig/suggestions');
    }
    
    public function getDefaultVideoWidth()
    {
    	return (int) Mage::getStoreConfig('videogallery/frontconfig/defaultwidth');
    }
    
    public function getDefaultVideoHeight()
    {
    	return (int) Mage::getStoreConfig('videogallery/frontconfig/defaultheight');
    }
    
    public function getVideoTemplate()
    {
		/*if (isset($_GET['vgds']))
    	{
    		$x = array('iceberg/videogallery/media.phtml',
    		'iceberg/videogallery/samples/media-tabbed1.phtml', 
    		'iceberg/videogallery/samples/media-tabbed2.phtml',
    		'iceberg/videogallery/samples/media-images-videos.phtml',
    		'iceberg/videogallery/samples/media-custom.phtml',
        	);
        	return $x[$_GET['vgds']-1];
    	}*/
    	
    	return Mage::getStoreConfig('videogallery/frontconfig/displaystyle');
    }
    
    
    public function getVimeoProAPICredentials()
    {
    	$credentials = array(
    		'api_key'             => Mage::getStoreConfig('videogallery/vimeopro/api_key'),
    		'api_secret'          => Mage::getStoreConfig('videogallery/vimeopro/api_secret'),
    		'access_token'        => Mage::getStoreConfig('videogallery/vimeopro/access_token'),
    		'access_token_secret' => Mage::getStoreConfig('videogallery/vimeopro/access_token_secret'),
    	);
    	
    	if ($credentials['api_key'] && $credentials['api_secret'] && $credentials['access_token'] && $credentials['access_token_secret'])
    	{
    		return $credentials;
    	}
    	
    	return false;
    }
    
    
    /* Curl stuff */
    function file_get_contents_curl($url)
	{
		$curl = curl_init();
		
		curl_setopt ($curl, CURLOPT_URL, $url);
		curl_setopt ($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt ($curl, CURLOPT_HEADER, 0);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
		$data = curl_exec ($curl);
		curl_close ($curl);

		return $data;
	}
	
	function isUrl($str)
	{
		return (substr($str, 0, 7) == 'http://') || (substr($str, 0, 8) == 'https://');
	}
	
	
	/**
	 * To get Video count, call:
	 * Mage::helper('videogallery')->getProductVideoCount($product);
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param Boolean $countDisabled
	 * @return int
	 */
	public function getProductVideoCount($product, $countDisabled=false)
	{
		$videoGallery = $product->getData('video_gallery');
		
		$count = 0;
		
		if (isset($videoGallery['videos']) && is_array($videoGallery['videos']))
		{
			if ($countDisabled)
			{
				$count = count($videoGallery['videos']);
			}
			else 
			{
				foreach ($videoGallery['videos'] as $video)
				{
					if (isset($video['disabled']) && $video['disabled']==true)
					{
						continue;
					}
					++$count;
				}
			}
		}
		return $count;
	}
	
	/**
	 * @return boolean
	 */
    public function isMobile()
    {
    	$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    	$userAgent = strtolower($userAgent);
    	
    	// Supported mobile devices
    	$match = array('mobile', 'iphone', 'ipad', 'ipod', 'android', 'tablet');
    	
    	foreach ($match as $value)
    	{
    		if (stristr($userAgent,$value))
    		{
    			return true;
    		}
    	}
    	
    	return false;
    }
}