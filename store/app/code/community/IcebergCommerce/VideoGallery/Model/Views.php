<?php 

class IcebergCommerce_VideoGallery_Model_Views
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'default', 'label'=>Mage::helper('videogallery')->__('Default')),
            array('value'=>'tabbed', 'label'=>Mage::helper('videogallery')->__('Use Tabs')),                    
        );
    }

}
