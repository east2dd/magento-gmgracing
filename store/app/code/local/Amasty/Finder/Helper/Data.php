<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */ 
class Amasty_Finder_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SORT_STRING_ASC  = 0;
    const SORT_STRING_DESC = 1;
    const SORT_NUM_ASC     = 2;
    const SORT_NUM_DESC    = 3;

    public function formatUrl($url){

        if (Mage::app()->getStore()->isCurrentlySecure()) {
            $securedFlag = true;
        } else {
            $securedFlag = false;
        }

        $fpcEnabled = Mage::helper('core')->isModuleEnabled('Enterprise_Pagecache');

        if ($fpcEnabled)
            $url.= strpos($url, '?')?'&no_cache=1':'?no_cache=1';

        if ($securedFlag)
            $url = str_replace("http://", "https://", $url);


        return Mage::helper('core')->urlEncode($url);
    }
    
}