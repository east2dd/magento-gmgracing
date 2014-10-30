<?php
/**
 * Categoryimport.php
 * CommerceThemes @ InterSEC Solutions LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.commercethemes.com/LICENSE-M1.txt
 *
 * @category   Category
 * @package    Categoryimport
 * @copyright  Copyright (c) 2003-2009 CommerceThemes @ InterSEC Solutions LLC. (http://www.commercethemes.com)
 * @license    http://www.commercethemes.com/LICENSE-M1.txt
 */ 
 
class Mage_Catalog_Model_Convert_Adapter_Categoryimport extends Mage_Eav_Model_Convert_Adapter_Entity
{
    protected $_categoryCache = array();

    protected $_stores;

    /**
     * Category display modes
     */
    protected $_displayModes = array( 'PRODUCTS', 'PAGE', 'PRODUCTS_AND_PAGE');

    public function parse()
    {
        $batchModel = Mage::getSingleton('dataflow/batch');
        /* @var $batchModel Mage_Dataflow_Model_Batch */

        $batchImportModel = $batchModel->getBatchImportModel();
        $importIds = $batchImportModel->getIdCollection();

        foreach ($importIds as $importId) {
            //print '<pre>'.memory_get_usage().'</pre>';
            $batchImportModel->load($importId);
            $importData = $batchImportModel->getBatchData();

            $this->saveRow($importData);
        }
    }

    /**
     * Save category (import)
     *
     * @param array $importData
     * @throws Mage_Core_Exception
     * @return bool
     */
    public function saveRow(array $importData)
    {
				
        if (empty($importData['store'])) {
            if (!is_null($this->getBatchParams('store'))) {
                $store = $this->getStoreById($this->getBatchParams('store'));
            } else {
                $message = Mage::helper('catalog')->__('Skip import row, required field "%s" not defined', 'store');
                Mage::throwException($message);
            }
        } else {
						#$store = Mage::getModel('core/store')->load($this->getBatchParams('store'));
            $store = $this->getStoreByCode($importData['store']);
        }

        if ($store === false) {
            $message = Mage::helper('catalog')->__('Skip import row, store "%s" field not exists', $importData['store']);
            Mage::throwException($message);
        }
				if(isset($importData['rootid']) && $importData['rootid']!="") {
					$rootId = $importData['rootid'];
				} else {
       	  $rootId = $store->getRootCategoryId();
				}
        if (!$rootId) {
            return array();
        }
        $rootPath = '1/'.$rootId;
        if (empty($this->_categoryCache[$store->getId()])) {
            $collection = Mage::getModel('catalog/category')->getCollection()
                ->setStore($store)
                ->addAttributeToSelect('name');
            $collection->getSelect()->where("path like '".$rootPath."/%'");

            foreach ($collection as $cat) {
                $pathArr = explode('/', $cat->getPath());
                $namePath = '';
                for ($i=2, $l=sizeof($pathArr); $i<$l; $i++) {
                    $name = $collection->getItemById($pathArr[$i])->getName();
                    $namePath .= (empty($namePath) ? '' : '/').trim($name);
                }
                $cat->setNamePath($namePath);
            }

            $cache = array();
            foreach ($collection as $cat) {
                $cache[strtolower($cat->getNamePath())] = $cat;
                $cat->unsNamePath();
            }
            $this->_categoryCache[$store->getId()] = $cache;
        }
        $cache =& $this->_categoryCache[$store->getId()];

        $importData['categories'] = preg_replace('#\s*/\s*#', '/', trim($importData['categories']));
        if (!empty($cache[$importData['categories']])) {
            return true;
        }

        $path = $rootPath;
        $namePath = '';

        $i = 1;
				$general = array();
        #$categories = explode(',', $importData['categories']);
        $categories = explode('/', $importData['categories']);
				$IsActive = $importData['is_active']; 
				$IsAnchor = $importData['is_anchor']; 
				$UrlKey = $importData['url_key']; 
				$UrlPath = $importData['url_path']; 
				$MetaTitle = trim($importData['meta_title']); 
				$MetaKeywords = trim($importData['meta_keywords']); 
				$MetaDescription = trim($importData['meta_description']); 
				$description = trim($importData['description']); 
				$dispMode = $importData['display_mode'];
				$cmsBlock = $importData['cms_block'];
				$pageLayout = $importData['page_layout'];
				$customDesign = $importData['custom_design'];
					
        foreach ($categories as $catName) {
								
						$namePath .= (empty($namePath) ? '' : '/').strtolower($catName);
            if (empty($cache[$namePath])) {
                #$dispMode = $this->_displayModes[2];
								/*
                $cat = Mage::getModel('catalog/category')
                    ->setStoreId($store->getId())
                    ->setPath($path)
                    ->setName($catName)
										->setAttributeSetId($cat->getDefaultAttributeSetId()) 
                    ->setIsActive($importData['is_active'])
                    ->setIsAnchor($importData['is_anchor'])
                    ->setDisplayMode($importData['display_mode'])
                    ->save();
								*/
								$cat = Mage::getModel('catalog/category'); 
								$cat->setStoreId($store->getId()); 
								$general['name'] = $catName; 
								$general['path'] = $path; 
								$general['meta_title'] = $MetaTitle; 
								$general['meta_keywords'] = $MetaKeywords; 
								$general['meta_description'] = $MetaDescription; 
								$general['description'] = $description; 
								$general['landing_page'] = $cmsBlock; 
								$general['display_mode'] = $dispMode; 
								$general['is_active'] = $IsActive; 
								$general['is_anchor'] = $IsAnchor; 
								$general['url_key'] = $UrlKey; 
								$general['url_path'] = $UrlPath; 
								if(isset($importData['page_layout'])) {
									$general['page_layout'] = $pageLayout; 
								}
								if(isset($importData['custom_design'])) {
									$general['custom_design'] = $customDesign; 
								}
								$cat->addData($general); 
								$cat->setDescription($description); 
								$cat->save();
								
                $cache[$namePath] = $cat;
            }
            $catId = $cache[$namePath]->getId();
            $path .= '/'.$catId;
            $i++;
						
        }
				#echo "ID: " . $catId;
				
				/* THIS IS FOR UPDATING CATEGORY DATA */
				$catupdate = Mage::getModel('catalog/category')->load($catId); 
				
				if($catupdate->getId() > 0) {
						$generalupdate['meta_title'] = $MetaTitle; 
						$generalupdate['meta_keywords'] = $MetaKeywords; 
						$generalupdate['meta_description'] = $MetaDescription; 
						$generalupdate['landing_page'] = $cmsBlock; 
						$generalupdate['display_mode'] = $dispMode; 
						$generalupdate['is_active'] = $IsActive; 
						$generalupdate['is_anchor'] = $IsAnchor; 
						$generalupdate['url_key'] = $UrlKey; 
						$generalupdate['url_path'] = $UrlPath; 
						if(isset($importData['page_layout'])) {
							$generalupdate['page_layout'] = $pageLayout; 
						}
						if(isset($importData['custom_design'])) {		
							$generalupdate['custom_design'] = $customDesign; 
						}
						$catupdate->addData($generalupdate); 
						$catupdate->setDescription($description); 
						$catupdate->save();
				}						
				/* END UPDATE CATEGORY DATA */
				
        /* ABILITY TO IMPORT IMAGE FOR CATEGORIES START */
				if(isset($importData['category_image']) && $importData['category_image'] != "") {
					$file = preg_replace('#\s*/\s*#', '/', trim($importData['category_image']));
					#echo "FILE: " . $file;
					$sourceFilePath = Mage::getBaseDir('media') . DS . 'import' . DS . $file;
					$targetFileName = $cache[$namePath]->getId().'-'.$file;
					if(file_exists($sourceFilePath) && !is_dir($sourceFilePath))
					{
							copy($sourceFilePath,Mage::getBaseDir('media') . DS . 'catalog'.DS.'category' . DS . $targetFileName);
							$cache[$namePath]->setImage($targetFileName)->save(); 
					}
				}
        /* ABILITY TO IMPORT IMAGE FOR CATEGORIES END */

        return true;
    }

    /**
     * Retrieve store object by code
     *
     * @param string $store
     * @return Mage_Core_Model_Store
     */
    public function getStoreByCode($store)
    {
        $this->_initStores();
        if (isset($this->_stores[$store])) {
            return $this->_stores[$store];
        }
        return false;
    }

    /**
     *  Init stores
     *
     *  @param    none
     *  @return      void
     */
    protected function _initStores ()
    {
        if (is_null($this->_stores)) {
            $this->_stores = Mage::app()->getStores(true, true);
            foreach ($this->_stores as $code => $store) {
                $this->_storesIdCode[$store->getId()] = $code;
            }
        }
    }
		protected function getStoreById($id)
   {
       $this->_initStores();
       /**
        * In single store mode all data should be saved as default
        */
       if (Mage::app()->isSingleStoreMode()) {
           return Mage::app()->getStore(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID);
       }

       if (isset($this->_storesIdCode[$id])) {
           return $this->getStoreByCode($this->_storesIdCode[$id]);
       }
       return false;
   }
}

?>