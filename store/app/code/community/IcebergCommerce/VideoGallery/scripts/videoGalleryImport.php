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
	//Format is product_id => array(video_url_1, video_url_2, ...)
	
	/*
	// Example Data
	1 => array(
		'http://www.youtube.com/watch?v=AAAA', 
		'http://www.youtube.com/watch?v=BBBB',
	),
	
	2 => array(
		'http://www.youtube.com/watch?v=CCCC',
	),
	*/
	
);

// -------------------------------
// Loop through Data
// Load Video from youtube/etc...
// Save to database
// -------------------------------
foreach ($data as $productId => $urls)
{
	$product = Mage::getModel('catalog/product')->load($productId);
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









