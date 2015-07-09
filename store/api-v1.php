<?php
/*
* author: xing
* email: xingxing2d@gmail.com
* date: 2014/03/28
*/

$mageFilename = getcwd() . '/app/Mage.php';
require $mageFilename;

if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}

ini_set('display_errors', 1);
// emulate index.php entry point for correct URLs generation in API
Mage::register('custom_entry_point', true);
Mage::$headersSentThrowsException = false;
Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_GLOBAL, Mage_Core_Model_App_Area::PART_EVENTS);
Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_ADMINHTML, Mage_Core_Model_App_Area::PART_EVENTS);

// query parameter "type" is set by .htaccess rewrite rule
$apiAlias = Mage::app()->getRequest()->getParam('type');
$request = Mage::app()->getRequest();

// Api logic goes under right here

if ($apiAlias == "product_search"){
    
    // simulate the magento search engine, copied from core
    $query = Mage::helper('catalogsearch')->getQuery();
    $query->setStoreId(Mage::app()->getStore()->getId());

    if ($query->getQueryText()) {
        if (Mage::helper('catalogsearch')->isMinQueryLength()) {
            $query->setId(0)
                ->setIsActive(1)
                ->setIsProcessed(1);
        }
        else {
            if ($query->getId()) {
                $query->setPopularity($query->getPopularity()+1);
            }
            else {
                $query->setPopularity(1);
            }
            $query->prepare();
        }
        Mage::helper('catalogsearch')->checkNotes();

        if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
            $query->save();
        }
    }

    $collection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();

    // compose results as json
    $results = array();
    $results['query'] = $query->getQueryText();
    $results['found'] = $collection->getSize();

    if ($results['found'] > 3)
    {
        $results['count'] = 3;
    }else{
        $results['count'] = $results['found'];
    }

    $i=0;

    $products = [];
    foreach ($collection as $product){
        $i++;
        if ($i > 3){
            break;
        }
        $products[] = array(
            'id'=>$product->getId(),
            'name'=>$product->getName(),
            'description'=>$product->getShortDescription(),
            'price'=>Mage::helper('core')->currency($product->getFinalPrice(), true, false),
            'url'=>$product->getProductUrl(),
            'image'=>(String)Mage::helper('catalog/image')->init($product, 'thumbnail')->resize(400)
        );
    }

    $results['items'] = $products;
    echo json_encode($results);
}