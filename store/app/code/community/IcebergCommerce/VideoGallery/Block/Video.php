<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * The media block on the product view page has been redirected to this block.
 * Mage_Catalog_Block_Product_View_Media => IcebergCommerce_VideoGallery_Block_Video
 * 
 * It should be noted, the layout file has also been changed.  Instead of using
 *  catalog/product/view/media.phtml
 * we now use
 *  iceberg/videogallery/media.phtml
 *  
 */
class IcebergCommerce_VideoGallery_Block_Video 
	extends Mage_Catalog_Block_Product_View_Media
{
    protected $_isVideoGalleryDisabled;
    protected $_oldTemplate;
    protected $_cacheVideoGallery;
    
    public function _toHtml()
    {
    	
    	// If default template was overridden, save for use later when outputting images gallery.
    	$this->_oldTemplate = $this->getTemplate() ? $this->getTemplate() : 'catalog/product/view/media.phtml';
    	
    	// Change template to videogallery template.
    	$this->setTemplate(Mage::helper('videogallery')->getVideoTemplate());
    	
    	return parent::_toHtml();
    }
    
    /**
     * Get list of videos associated with product.
     */
	public function getVideoGallery()
	{	
		if (!$this->_cacheVideoGallery)
		{
			$this->_cacheVideoGallery = array();
			
			if (is_array($this->getProduct()->getData('video_gallery'))) {
				$value = $this->getProduct()->getData('video_gallery');
				if (count($value['videos'])>0) {
					$this->_cacheVideoGallery = $value['videos'];
					foreach ($value['videos'] as $key=>$video) {
						if( $video['disabled'] )
							unset( $this->_cacheVideoGallery[ $key ]);
						else	
							$this->_cacheVideoGallery[$key]['url'] = Mage::getSingleton('catalog/product_media_config')->getMediaUrl($video['file']);
					}
				}
			}
		}
		
		return $this->_cacheVideoGallery;
	}
    
    /**
     * Get Single Video Block html output.
     */
    public function getVideoObjectBlock( $video , $width = 267, $height = null )
    {
    	$data = array(
    		'video' => $video , 
    		'width' => $width,					// Config option?
    		'height' => $height > 0 ? $height : floor( $width/1.6) );
    	
    	return $this->getLayout()->createBlock('videogallery/single')->setData( $data )->toHtml();
    }

    /**
     * Get URL to Video Gallery.
     */
    public function getVideoGalleryUrl($video=null)
    {
        $params = array('product_id'=>$this->getProduct()->getId() );
        if ($video) {
        	
            $params['media_video_id'] = $video['value_id'];
            return $this->getUrl('videogallery/gallery/single/', $params);
        }
        return $this->getUrl('videogallery/gallery/single/', $params);
    }
    
    /**
     * Get Old Images media. While restoring previous settings to not effect other possible modifications.
     */
    public function getImagesMedia($template='')
    {
    	if (Mage::getStoreConfig('videogallery/frontconfig/imagedisplay'))
    	{
    		$template = 'iceberg/videogallery/samples/media-images.phtml';
    	}
    	
    	return $this->getImagesBlock()->setTemplate( $template ? $template : $this->_oldTemplate )->toHtml();
    }
    
    /**
     * Get Block to render product images.  defined in layout XML
     */
    public function getImagesBlock()
    {
    	return $this->getLayout()->createBlock('catalog/product_view_media');
    }
    
    
	public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }
    
    /**
     * Get Main Video.  Currently just returns first video in the Media Gallery.
     * 
     * ToDo: add functionality to define main video.
     */
    public function getMainVideo()
    {
    	$video = $this->getVideoGallery();
    	if( !count( $video) )
    		return array();
    		
    	return $video[0];
    }
    
    /**
     * Returns the Video Provider of the main video.
     */
    public function getMainVideoProvider()
    {
    	$video = $this->getMainVideo();
    	if( !isset( $video['provider'] ) )
    		return '';
    		
    	return $video['provider'];
    }
    
    /**
     * Returns the ID of the main video.
     */
	public function getMainVideoId()
    {
    	$video = $this->getMainVideo();
    	if( !isset( $video['value_id']) )
    		return '';
    		
    	return $video['value_id'];
    }
    
    public function isMobile()
    {
    	return Mage::helper('videogallery')->isMobile() && !$this->isTablet();
    }
    
    
    /**
	 * @return boolean
	 */
    public function isTablet()
    {
    	$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    	$userAgent = strtolower($userAgent);
    	
    	
    	// Detect ipad
    	if (stristr($userAgent,'ipad'))
    	{
    		return true;
    	}
    	
    	// Detect android tablet
    	if (stristr($userAgent,'android') && !stristr($userAgent,'mobile'))
    	{
    		return true;
    	}
    	
    	if (stristr($userAgent,'tablet'))
    	{
    		return true;
    	}

    	return false;
    }
    
    
    public function getMobileEmbed($video, $width, $height, $autoplay=true)
    {
    	// todo - the object should initialize with all data in the future.
    	// this will allow us to set video tag poster, among other things
    	return Mage::helper('videogallery/video')->getVideoByValue($video['value'],$video['provider'])->getMobileEmbedCode($width,$height,$autoplay);
    }
    
}