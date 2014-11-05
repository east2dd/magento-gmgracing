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

// emulate index.php entry point for correct URLs generation in API
Mage::register('custom_entry_point', true);
Mage::$headersSentThrowsException = false;
Mage::init('admin');
Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_GLOBAL, Mage_Core_Model_App_Area::PART_EVENTS);
Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_ADMINHTML, Mage_Core_Model_App_Area::PART_EVENTS);

// query parameter "type" is set by .htaccess rewrite rule
$apiAlias = Mage::app()->getRequest()->getParam('type');
$request = Mage::app()->getRequest();

if($apiAlias == "category_children"){
    $category_id = $request->getParam("id");
    $category  = Mage::getModel('catalog/category')->load($category_id);
    $child_categories = $category->getChildrenCategories();

    $results = [];
    foreach($child_categories as $_category)
    {
        $_category  = Mage::getModel('catalog/category')->load($_category->getId());

        $url = str_replace('api-v1.php', 'index.php', $_category->getUrl());
        $results[] = array(
            'id'=>$_category->getId(), 
            'url' => $url, 
            'label'=>$_category->getName(),
            'img_url'=> Mage::getBaseUrl('media').'catalog/category/'. $_category->getThumbnail(),
            'product_count'=>$_category->getProductCount()
        );
    }

    echo json_encode($results);
}elseif ($apiAlias == "product_gmg_categories"){
    $product_id = $request->getParam("id");
    $product = Mage::getModel('catalog/product')->load($product_id);

    $year_pid = 2;

    $cats = $product->getCategoryIds();
    foreach ($cats as $category_id) {
        $_cat = Mage::getModel('catalog/category')->load($category_id);
        $_parent_id = $_cat->getParentCategory()->getId();
        if($_parent_id == $year_pid){
            $_parent = Mage::getModel('catalog/category')->load($_parent_id);
            echo $_cat->getName();
        }
    }
}