<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */ 
class Amasty_Finder_Block_Adminhtml_Finder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('finderTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amfinder')->__('Finder Options'));
    }

    protected function _beforeToHtml()
    {
        $this->_addSimpleTab('general');
        if (Mage::registry('amfinder_finder')->getId()){  
            $this->_addSimpleTab('dropdowns');   
            $this->_addSimpleTab('import');   
            
            $this->addTab('products', array(
                'label'     => Mage::helper('amfinder')->__('Products'),
                'class'     => 'ajax',
                'url'       => $this->getUrl('adminhtml/finder/products', array('_current' => true)),
            ));
            
            if (Mage::getStoreConfig('amfinder/general/universal')) {
                $this->_addSimpleTab('universalimport', 'Universal Import'); 
                $this->addTab('universalproducts', array(
                    'label'     => Mage::helper('amfinder')->__('Universal Products'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('adminhtml/finder/universalproducts', array('_current' => true)),
                ));  
            }                        
        }   
        
        return parent::_beforeToHtml();
    }
    
    protected function _addSimpleTab($code, $label='')
    {
        if (!$label)
            $label = ucfirst($code);
            
        $label = Mage::helper('amfinder')->__($label);
        $this->addTab($code, array(
            'label'     => $label,
            'content'   => $this->getLayout()->createBlock('amfinder/adminhtml_finder_edit_tab_' . $code)
                ->setTitle($label)->toHtml(),
        ));
                   
        return $this;        
    }
}