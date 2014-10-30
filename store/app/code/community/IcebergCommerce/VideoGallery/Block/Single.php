<?php
/**
 * Iceberg Commerce
 *
 * @author     IcebergCommerce
 * @package    IcebergCommerce_VideoGallery
 * @copyright  Copyright (c) 2010 Iceberg Commerce
 */

class IcebergCommerce_VideoGallery_Block_Single extends Mage_Core_Block_Template
{
	protected $_model;
	
	protected function _toHtml()
	{
		if (!$this->getVideo())
		{
			return false;
		}
	
		// Set template file.
		$this->setTemplate( 'iceberg/videogallery/single.phtml' );
		return parent::_toHtml();
	}
	
	public function getModel()
	{
		if (!isset( $this->_model))
		{
			$this->_model = Mage::helper('videogallery/video')->getVideoByValue( $this->getVideoValue() , $this->getProvider() );
		}
		return $this->_model;
	}
	
	public function getEmbedCode($width, $height=null, $autoplay=true)
	{
		return $this->getModel()->getEmbedCode($width, $height, $autoplay);
	}
	
	public function getMobileEmbedCode($width, $height=null, $autoplay=true)
	{
		return $this->getModel()->getMobileEmbedCode($width, $height, $autoplay);
	}

	public function getVideoById( $mediaVideoId )
	{
		$current_video = Mage::registry('current_video');
		
		if ($current_video)
		{
			return $current_video;
		}
		
		$product = $this->getProduct();
		
		if ($product && is_array($this->getProduct()->getData('video_gallery'))) 
		{
			$value = $product->getData('video_gallery');
			if(count($value['videos'])>0) 
			{
				foreach ($value['videos'] as $video) 
				{
					$video['url'] = Mage::getSingleton('catalog/product_media_config')
					->getMediaUrl($video['file']);

					if( $video['value_id'] == $mediaVideoId )
					{
						Mage::register('current_video', $video);
						return $video;
					}
				}
			}
		}
		 
		return array();
	}
	
	
	public function getVideo()
	{
		if ( $this->getData('video') ) {
			return $this->getData('video');
		}
		
		$mediaVideoId = $this->getMediaVideoId();
		
		if (is_numeric($mediaVideoId) && $mediaVideoId > 0)
		{
			$video = $this->getVideoById($mediaVideoId);
			
			if ($video)
			{
				return $video;
			}
		}

		return null;
	}

	public function getProduct()
	{
		$product = Mage::registry('product');
		
		if (!$product)
		{
			$productId = $this->getProductId();
			
			if (is_numeric($productId) && $productId > 0)
			{
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productId);
				
				if ($product)
				{
					Mage::register('product', $product);
				}
			}
		}

		return Mage::registry('product');
	}

	public function getVideoId()
	{
		$video = $this->getVideo();
		 
		return $video['value_id'];
	}
	
	public function getVideoValue()
	{
		$video = $this->getVideo();
		 
		return $video['value'];
	}
	
	public function getLabel()
	{
		$video = $this->getVideo();
		 
		return $video['label'];
	}
	
	public function getProvider()
	{
		$video = $this->getVideo();
		 
		return $video['provider'];
	}
	
	public function getDescription()
	{
		$video = $this->getVideo();
		
		return $video['description'];
	}
	
	public function getJsObjectName()
	{
		return $this->getHtmlId() . 'JsObject';
	}
	
	public function getWidth()
	{
		return $this->getData('width');
	}
	
	public function getHeight()
	{
		return $this->getData('height');
	}
	
}