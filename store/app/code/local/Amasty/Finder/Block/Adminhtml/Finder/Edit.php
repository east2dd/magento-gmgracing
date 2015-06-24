<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */ 
class Amasty_Finder_Block_Adminhtml_Finder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id'; 
        $this->_blockGroup = 'amfinder';
        $this->_controller = 'adminhtml_finder';
        
        if (Mage::registry('amfinder_finder')->getId()){
            $this->_addButton('save_and_continue', array(
                    'label'     => Mage::helper('salesrule')->__('Save and Continue Edit'),
                    'onclick'   => 'saveAndContinueEdit()',
                    'class' => 'save'
                ), 10);
            $this->_formScripts[] = " function saveAndContinueEdit(){ editForm.submit($('edit_form').action + 'continue/edit') } ";        
        }
        
        $mid = Mage::registry('amfinder_finder')->getId();
        if ($mid) {
            $this->_addButton('new', array(
                    'label' => Mage::helper('amfinder')->__('Add New Record'),
                    'onclick' => 'newFinder()',
                    'class' => 'add'
                ),15);

            $url = $this->getUrl('adminhtml/value/new', array('finder'=>$mid));  
            $this->_formScripts[] = " function newFinder(){ setLocation('$url'); } ";

            $this->_formScripts[10000] ="
                document.observe('dom:loaded', function()
                {
                    $$('#finderTabs li a').invoke( 'observe', 'click', function(item) {

                        switch (this.id)
                        {
                            case 'finderTabs_general':
                            case 'finderTabs_dropdowns':
                            case 'finderTabs_import':
                            case 'finderTabs_products':
                                $$('.form-buttons .scalable.add').each(function(item) {
                                    item.show();
                                });
                                break;

                            case 'finderTabs_universalimport':
                            case 'finderTabs_universalproducts':
                                $$('.form-buttons .scalable.add').each(function(item) {
                                    item.hide();
                                });
                                break;
                        }
                    });
                    setTimeout(toggleButton, 40);
                });
                function toggleButton()
                {
                    if($('finderTabs').down('a.active').id == 'finderTabs_universalimport'
                        || $('finderTabs').down('a.active').id == 'finderTabs_universalproducts')
                    {
                        $$('.form-buttons .scalable.add').each(function(item) {
                            item.hide();
                        });
                    }
                }
            ";
        }

    }

    public function getHeaderText()
    {
        $header = Mage::helper('amfinder')->__('New Parts Finder');
        if (Mage::registry('amfinder_finder')->getId()){
            $header = Mage::helper('amfinder')->__('Edit Parts Finder `%s`', Mage::registry('amfinder_finder')->getName());
        }
        return $header;
    }
}