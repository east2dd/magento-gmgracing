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
class IcebergCommerce_VideoGallery_Helper_Video extends Mage_Core_Helper_Abstract
{	
	/**
	 * Get Enable Video Platforms
	 * @return array
	 */
	public function getProviders()
	{
		if (defined('COMPILER_INCLUDE_PATH')) 
		{
			return $this->getProvidersMagentoCompilerEnabled();
		}
		
		$files = scandir(dirname(dirname(__FILE__)) . '/Model/Video');
		
		$providers = array();
		
		foreach ($files as $file)
		{
			if (preg_match('#(.*).php$#', $file, $match))
			{
				$providers[] = strtolower($match[1]);
			}
		}
		
		// If enabled, Add locally hosted video provider
		if (file_exists(dirname(dirname(__FILE__)) . '/Model/Video/Localhost/Flowplayer.php'))
		{
			$providers[] = 'localhost_flowplayer';
		}
		
		return $providers;
	}
	
	private function getProvidersMagentoCompilerEnabled()
	{
		$files = scandir(dirname(__FILE__));
		$providers = array();
		
		foreach ($files as $file)
		{
			if (preg_match('#IcebergCommerce_VideoGallery_Model_Video_(.*).php$#', $file, $match))
			{
				$providers[] = strtolower($match[1]);
			}
		}
		
		return $providers;
	}
	
	/**
	 * Try to match the video url to one of the video platforms enabled
	 * @return IcebergCommerce_VideoGallery_Model_Video $video
	 */
	public function getVideoByUrl($url)
	{
		$providers = $this->getProviders();
		
		foreach ($providers as $provider)
		{
			$model = Mage::getModel('videogallery/video_' . $provider);
			if (!$model)
			{
				continue;
			}
			
			if ($model->setVideoByUrl($url) !== false)
			{
				return $model;
			}
		}
		
    	return false;
	}
	
	/**
	 * Load correct video model
	 * @return IcebergCommerce_VideoGallery_Model_Video $video
	 */
	public function getVideoByValue($id, $provider)
	{
		// Create Object
		$model = Mage::getModel('videogallery/video_' . $provider);
		if (!$model)
		{
			return null;
		}
		
		// Load Video
		$model->setVideoByValue($id);
		return $model;
	}
}