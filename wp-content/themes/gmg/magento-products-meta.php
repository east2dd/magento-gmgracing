<?php 
/**
 * This function is used to output Magento products. You may customize the output to your likings, using the following preset functions.
 * 
 * magento_have_products()			--	Checks wether there's a product to be displayed or not. We recommend you to put it in a 'while(magento_have_products()):' loop. Also, all functions below should be within this loop. They will not work when not in this loop.
 * magento_product_title()			--	Outputs the product title.
 * magento_product_url()			--	Outputs the product url.
 * magento_product_price()			--	Outputs the product price. This function shows the price or when there's a discount, it shows the price after discount.
 * magento_product_default_price()	--	Outputs the products default price in format 0.00. This is the price without discount.
 * magento_product_special_price()	--	Outputs the discount price in format 0.00.
 * magento_has_image()				--	Checks wether there's an image to display or not.
 * magento_product_thumbnail_url()	--	Outputs the url to the first image of a product. A clever thing to do, would be putting this inside a 'if(magento_has_image()):' check.
 * magento_have_images()			--	Check wether there's an image to be displayed or not. We recommend you to put it in a 'while(magento_have_images())' loop. \ These
 * magento_product_image_url()		--	Outputs the url to the image. This function should be placed within a magento_have_images() loop otherwise it won't work.  / These functions are for displaying every single image of a product.
 * 
 * @return String $content
 */?>