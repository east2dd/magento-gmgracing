<?php
// Sample script to bulk import videos into video gallery

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

Mage::app('admin');
Mage::app('admin')->setUseSessionInUrl(false);

// ------------------------
// Setup
// ------------------------
$attributeModel = Mage::getResourceModel('catalog/eav_attribute')->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product'));
$attributeModel->load('video_gallery', 'attribute_code');
$videoBackendModel = Mage::getModel('videogallery/backend_video')->setAttribute($attributeModel);
$resourceModel = Mage::getResourceSingleton('videogallery/backend_video');

// ------------------------
// Data to insert
// ------------------------
$data = array(
	//Format is sku => array(video_url_1, video_url_2, ...)
	
	/*
	// Example Data
	'sku1' => array(
		'http://www.youtube.com/watch?v=AAAA', 
		'http://www.youtube.com/watch?v=BBBB',
	),
	
	'sku2' => array(
		'http://www.youtube.com/watch?v=CCCC',
	),
	*/
	
	'sss' => array(
		'http://www.youtube.com/watch?v=P_hqo5orI40', 
		'http://www.youtube.com/watch?v=-xcY2l_A4_U',
	),
	
	
	'500gb7200' => array(
		'http://www.youtube.com/watch?v=BBBB',
	),
	
	
	
);

// -------------------------------
// Loop through Data
// Load Video from youtube/etc...
// Save to database
// -------------------------------
foreach ($data as $sku => $urls)
{
	$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
	
	
	if (!$product)
	{
		echo "\n[Error] Could not find SKU $sku. Invalid SKU.\n";
		continue;
	}
	
	$productId = $product->getId();
	
	try {
		
	foreach ($urls as $url)
	{
		$video = $videoBackendModel->addVideo($product, $url);
		
		$catalogProductEntityMediaVideoGallery = array(
			'entity_id' => $productId,
			'attribute_id' => $attributeModel->getId(),
			'thumbnail' => $video['file'],
			'provider' => $video['provider'],
			'value' => $video['value'],
		);
		
		$valueId = $resourceModel->insertGallery($catalogProductEntityMediaVideoGallery);

		$catalogProductEntityMediaVideoGalleryValue = array(
			'value_id' => $valueId,
			'label' => '',
			'description' => '',
			'position' => 1,
			'disabled' => false,
			'store_id' => 0
		);
		
		$resourceModel->insertGalleryValueInStore($catalogProductEntityMediaVideoGalleryValue);
	}
	}
	catch (Exception $e)
	{
		echo "\n[Error] Error inserting videos for SKU $sku.  Check video urls.\n";
	}
}









