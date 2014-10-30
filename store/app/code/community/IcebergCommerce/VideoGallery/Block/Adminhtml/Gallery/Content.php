<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

/**
 * For Product Edit Page Video Tab
 */
class IcebergCommerce_VideoGallery_Block_Adminhtml_Gallery_Content extends Mage_Adminhtml_Block_Widget
{
	protected $_youtubeFeed;
	
	/**
	 * Initialize
	 * set template for video tab on product edit
	 */
    public function __construct()
    {
        parent::__construct();
        
        // Set template for content block.
        $this->setTemplate('iceberg/videogallery/gallery.phtml');
    }

    /**
     * @return string
     */
    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    /**
     * Get HTML of Add Video Button
     */
    public function getAddVideoButton()
    {
        return $this->getButtonHtml(
            Mage::helper('catalog')->__('Add New Video'),
            $this->getJsObjectName() . '.addNewVideoButton();',
            'add',
            $this->getHtmlId() . '_add_images_button'
        );
    }
    
    /**
     * @return url
     */
	public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'tab'        => '{{tab_id}}',
            'active_tab' => null
        ));
    }	
    
    /**
     * Get array of product videos
     * @return array
     */
    public function getVideos()
    {
    	if (is_array($this->getElement()->getValue())) 
    	{
            $value = $this->getElement()->getValue();
            if (count($value['videos']) > 0) 
            {
                foreach ($value['videos'] as &$video) 
                {
                    $video['url'] = Mage::getSingleton('catalog/product_media_config')->getMediaUrl($video['file']);
                }
                return $value['videos'];
            }
        }
        return array();
    }
    
    /**
     * @return array
     */
    public function getProviders()
    {
    	$providers = Mage::helper( 'videogallery/video' )->getProviders();
    	
    	$list = array();
    	foreach ($providers as $provider)
    	{
    		$model = Mage::getModel('videogallery/video_' . $provider);
			if (!$model)
			{
				continue;
			}
			$list[] = $model->getDisplayName();
    	}
    	
    	return $list;
    }

    /**
     * @return string
     */
    public function getVideosJson()
    {
    	$out = $this->getVideos();
    	
    	if (empty($out))
    	{
    		return '[]';
    	}
    	
    	return Mage::helper('videogallery')->jsonEncode($out);
    }
    
    public function getVideoCheckUrl()
    {
    	return $this->getUrl('adminhtml/videogallery_index/videocheck');
    }
    
    public function getProduct()
    {
    	return Mage::registry('current_product');
    }
    
    public function getYoutubeVideoSuggestions()
    {
    	if (Mage::helper('videogallery')->isVideoSuggestionsEnabled() == false)
    	{
    		return array();
    	}
    	
    	if( !$this->_youtubeFeed )
    	{
    		try 
    		{
		    	$product = $this->getProduct();
		    	
		    	if( !$product->getId() ) {
		    		return array();
		    	}
		    	
				$yt = new Zend_Gdata_YouTube();
				$yt->getHttpClient()->setConfig(array('timeout'=>10));

				$query = $yt->newVideoQuery();
				$query->videoQuery = $product->getName() . ' ' . $product->getSku();
				$query->startIndex = 0;
				$query->maxResults = 5;
				
				$results = $yt->getVideoFeed($query);
				
				$this->_youtubeFeed = array();
				foreach ($results as $video)
				{
					if ($this->videoAlreadyAdded($video->getVideoId()))
					{
						continue;
					}

					$this->_youtubeFeed[] = $video;
				}
    		}
    		catch (Exception $e)
    		{
    			return "Error Retrieving Video Suggestions from Youtube: ". $e->getMessage();
    		}
    	}
		
		return $this->_youtubeFeed;
    }
    
    public function getExistingVideoIds()
    {
    	if( !$this->hasExistingVideoIds() )
    	{
	    	$gallery = $this->getProduct()->getData('video_gallery');
	    	$ids = array();
	    	
	    	foreach( $gallery['videos'] as $video )
	    	{
	    		$ids[] = $video['value'];
	    	}
	    	
	    	$this->setExistingVideoIds( $ids );
    	}
    	return $this->getData('existing_video_ids');
    }

	/**
     * Get HTML of Add Video Button
     */
    public function getAddYoutubeVideoButton( $onClick )
    {
        return $this->getButtonHtml(
            Mage::helper('catalog')->__('Add Youtube Video'),
            $onClick,
            'add',
            $this->getHtmlId() . '_add_youtube_button'
        );
    }
    
    public function videoAlreadyAdded ( $id )
    {
    	return in_array( $id , $this->getExistingVideoIds() );
    }

}