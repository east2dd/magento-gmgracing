<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2011 Iceberg Commerce
 */
class IcebergCommerce_VideoGallery_Adminhtml_Videogallery_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function videocheckAction()
    {
    	$url = $this->getRequest()->getPost('url');
    	
    	$video = null;
    	$data = array('result' => false , 'message' => '');
    	
    	try 
    	{
	    	$video = Mage::helper('videogallery/video')->getVideoByUrl($url);
	    	
	    	if (!$video )
	    	{
	    		$data = array('result' => false , 'message' => 'Could not locate a supported video at that URL.' );
	    	}
    	}
    	catch (Exception $e)
    	{
    		$data = array('result' => false , 'message' => $e->getMessage());
    	}
    	
    	
    	if ($video)
    	{
    		$thumbnail = $video->getThumbnail() ? $video->getThumbnail() : $this->getPlaceholder();
    		$data = array(
    			'result' => true,
	    		'value' => $video->getVideoValue(),
		    	'provider' => $video->getProvider(),
		    	'thumbnail' => $thumbnail,
		    	'label' => trim($video->getLabel()),
		    	'description' => trim($video->getDescription()),
    		);
    	}
    	
    	$this->getResponse()->setBody( Mage::helper('videogallery')->jsonEncode($data) );
    }
    
    protected function getPlaceholder()
    {
    	$baseDir = Mage::getBaseDir('media');
        
		// Check if placeholder defined in config
        $isConfigPlaceholder = Mage::getStoreConfig("catalog/placeholder/image_placeholder");
        $configPlaceholder   = '/placeholder/' . $isConfigPlaceholder;
        if ($isConfigPlaceholder && file_exists($baseDir . $configPlaceholder)) {
            $thumbnail = $configPlaceholder;
        }
        else {
            // Replace file with skin or default skin placeholder
            $skinBaseDir     = Mage::getDesign()->getSkinBaseDir();
            $skinPlaceholder = "/images/catalog/product/placeholder/image.jpg";
            $thumbnail = $skinPlaceholder;
            if (file_exists($skinBaseDir . $thumbnail)) {
                $skinDir = $skinBaseDir;
            }
            else {
                $skinDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default'));
                if (!file_exists($skinDir . $thumbnail)) {
                    $skinDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'base'));
                }
                if (!file_exists($skinDir . $thumbnail)) {
                    $skinDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'default'));
                }
            }
            $thumbnail = str_replace('adminhtml','frontend', $skinDir) . $thumbnail;
        }
        
        return str_replace( Mage::getBaseDir() , "", $thumbnail);
    }
}
