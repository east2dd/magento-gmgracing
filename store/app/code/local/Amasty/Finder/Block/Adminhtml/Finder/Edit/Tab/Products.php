<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */ 
class Amasty_Finder_Block_Adminhtml_Finder_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_finder = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setId('amfinderProducts');
        $this->setUseAjax(true);
    }
    
    public function getFinder()
    {
        if (is_null($this->_finder)){
            $id = $this->getRequest()->getParam('id');
            $this->_finder = Mage::getModel('amfinder/finder')->load($id);
        }
        return $this->_finder;        
    }

    protected function _prepareCollection()
    {
        $products = Mage::getModel('amfinder/value')->getCollection()
            ->joinAllFor($this->getFinder());
        
        $this->setCollection($products);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        foreach($this->getFinder()->getDropdowns() as $d) {
            $i = $d->getPos();
            $this->addColumn("name$i", array(
                'header'    => $d->getName(),
                'index'     => "name$i",
                'filter_index'     => "d$i.name",
            ));        
        }
        
        $this->addColumn('sku', array(
            'header'    => Mage::helper('amfinder')->__('SKU'),
            'index'     => 'sku',
        ));
        
        $this->addColumn('action', array(
                    'header'    => Mage::helper('amfinder')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'    => 'getVid',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('amfinder')->__('Delete'),
                            'url'     => array('base'=>'adminhtml/finder/proddelete'),
                            'field'   => 'vid',
                            'confirm' => Mage::helper('amfinder')->__('Are you sure?')
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));         
        
        $this->addExportType('adminhtml/finder/productsCsv', Mage::helper('amfinder')->__('CSV'));
                
        return parent::_prepareColumns();
    }
     
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/value/edit', array('id' => $row->getVid())); 
    }
}
