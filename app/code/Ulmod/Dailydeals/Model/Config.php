<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Model;

use Magento\Store\Model\ScopeInterface;
use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    /**
     * Extension enabled config path
     */
    const XML_PATH_EXTENSION_ENABLED 							= 'um_dailydeal/general/is_enabled';
    const XML_PATH_LINKS_SHOWITEM_TOP                           = 'um_dailydeal/links/show_item_top';
    const XML_PATH_LINKS_ITEMTEXT_TOP                           = 'um_dailydeal/links/item_text_top';  
    const XML_PATH_LINKS_SHOWITEM_FOOTER                        = 'um_dailydeal/links/show_item_footer';
    const XML_PATH_LINKS_ITEMTEXT_FOOTER                        = 'um_dailydeal/links/item_text_footer'; 
    const XML_PATH_INDEX_PAGE_TITLE                        		= 'um_dailydeal/index_page/title'; 
    const XML_PATH_CONTENT_SHOW_DESCRIPTION                     = 'um_dailydeal/content/show_description'; 
    const XML_PATH_CONTENT_DESCRIPTION                         	= 'um_dailydeal/content/description'; 
    const XML_PATH_CONTENT_SHOW_COUNTDOWN                       = 'um_dailydeal/content/show_countdown'; 	
    const XML_PATH_CONTENT_COUNTDOWN_LABEL                      = 'um_dailydeal/content/countdown_label'; 
    const XML_PATH_CONTENT_SHOW_DISCOUNT_LABEL                  = 'um_dailydeal/content/show_discount_label';
    const XML_PATH_CONTENT_ITEMS_NUMBER                       	= 'um_dailydeal/content/items_number';
    const XML_PATH_SIDEBAR_IS_ENABLED                       	= 'um_dailydeal/sidebar/is_enabled';
    const XML_PATH_SIDEBAR_TITLE                       			= 'um_dailydeal/sidebar/title';
    const XML_PATH_SIDEBAR_ITEMS_NUMBER                       	= 'um_dailydeal/sidebar/items_number';
    const XML_PATH_SIDEBAR_IS_SLIDER_ENABLED                    = 'um_dailydeal/sidebar/is_slider_enabled';
    const XML_PATH_SIDEBAR_SHOW_COUNTDOWN                       = 'um_dailydeal/sidebar/show_countdown';
    const XML_PATH_SIDEBAR_COUNTDOWN_LABEL                      = 'um_dailydeal/sidebar/countdown_label';
    const XML_PATH_SIDEBAR_SHOW_DISCOUNT_LABEL                  = 'um_dailydeal/sidebar/show_discount_label';

    /**
     * @var DailydealFactory
     */
    private $dailydealFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
	
    /**
     * @var ProductFactory
     */	
    private $productFactory;

    /**
     * @var DateTime
     */
    private $dateTime;   

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;    
	
    /**
	 * @param DailydealFactory $dailydealFactory,
     * @param ScopeConfigInterface $scopeConfig,
     * @param ProductFactory $productFactory	 
     */    
    public function __construct(
		DailydealFactory $dailydealFactory,
        ScopeConfigInterface $scopeConfig,
    	DateTime $dateTime,
        StoreManagerInterface $storeManager,		
        ProductFactory $productFactory
    ) {    
        $this->dailydealFactory = $dailydealFactory;
        $this->scopeConfig = $scopeConfig;
        $this->dateTime = $dateTime;       
        $this->storeManager = $storeManager;        
        $this->productFactory = $productFactory;
    }

    /**
     * Get System Config values
     *
     * @return string|int|array|null
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is the module enabled in configuration or not
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EXTENSION_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get index page title
     *
     * @return string
     */
    public function getIndexPageTitle()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_INDEX_PAGE_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show daily deals link in top?
     *
     * @return bool
     */
    public function isShowItemTop()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LINKS_SHOWITEM_TOP,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Top link text
     *
     * @return string
     */
    public function getTopLinkText()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LINKS_ITEMTEXT_TOP,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show daily deals link in footer?
     *
     * @return bool
     */
    public function isShowItemFooter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LINKS_SHOWITEM_FOOTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Footer link text
     *
     * @return string
     */
    public function getFooterLinkText()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LINKS_ITEMTEXT_FOOTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is show description 
     *
     * @return int
     */
    public function isShowDescription()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_SHOW_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }
	
    /**
     *  Get description 
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is show countdown timer
     *
     * @return int
     */
    public function isShowCountdown()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_SHOW_COUNTDOWN,
            ScopeInterface::SCOPE_STORE
        );
    }
	
    /**
     *  Get countdown label 
     *
     * @return string
     */
    public function getCountdownLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_COUNTDOWN_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get max product numbers to display in main page
     *
     * @return string
     */
    public function getContentItemsNumber()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_ITEMS_NUMBER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is sidebar enabled?
     *
     * @return int
     */
    public function isSidebarEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_IS_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Get sidebar title
     *
     * @return string
     */
    public function getSidebarTitle()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Get sidebar items number
     *
     * @return int
     */
    public function getSidebarItemsNumber()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_ITEMS_NUMBER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is sidebar slider enabled
     *
     * @return int
     */
    public function isSidebarSliderEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_IS_SLIDER_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is show countdown in sidebar?
     *
     * @return int
     */
    public function isShowSidebarCountdown()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_SHOW_COUNTDOWN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Get sidebar countdown label
     *
     * @return string
     */
    public function getSidebarCountdownLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_COUNTDOWN_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *  Is show discount label is sidebar
     *
     * @return int
     */
    public function isSidebarShowDiscountLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SIDEBAR_SHOW_DISCOUNT_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }
	
    /**
     *  Is show discount label? 
     *
     * @return int
     */
    public function isShowDiscountLabel()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTENT_SHOW_DISCOUNT_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }	

    /**
     * Check Dailydeal Product 
     *
     * @return bool
     */	
    public function isDealProduct($productId)
    {
        if(!$this->isEnabled())
            return false;
            $productcollection = $this->productFactory->create()->getCollection();
            $productcollection->addAttributeToSelect('*');
            $productcollection->addAttributeToFilter('entity_id', ['eq'=>$productId]);
            $sku=$productcollection->getFirstItem()->getSku();
            
            $dailydealcollection = $this->getDailydealcollection();
            $dailydealcollection->addFieldToSelect('*');
            $dailydealcollection->addFieldToFilter('um_product_sku', ['eq'=>$sku]);
            
        if ($dailydealcollection->getSize() ==1) {
            $currentDate = strtotime($this->getcurrentDate());
            $dealTodate = strtotime($this->getDailydealToDate($sku));
            $dealFromdate = strtotime($this->getDailydealFromDate($sku));
            
            if (( $currentDate <= $dealTodate ) && ($currentDate >= $dealFromdate)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get Bundle Discount Value
     *
     * @return string
     */		
    public function getbundleProductDiscount($sku)
    {
        $dailydealcollection = $this->getDailydealcollection();

        $umDiscountType = $dailydealcollection->getFirstItem()
            ->getUmDiscountType();    

        $umDiscountAmount = $dailydealcollection->getFirstItem()
            ->getUmDiscountAmount();     

        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter('um_product_sku', ['eq'=>$sku]);

        if ($umDiscountType ==1) {
            return '<div style=" margin-top:20px; "><strong>Save:
            '.$this->getcurrencySymbol().'
			'.number_format($umDiscountAmount, 2).'</strong></div>';
        } elseif ($umDiscountType ==2) {
            return '<div style="margin-top:20px;"><strong>OFF:
			'.number_format($umDiscountAmount, 2).'%</strong></div>';
        }
    }
	
    /**
     * Get Product Price
     *
     * @return int
     */     
    public function getProductPrice($sku)
    {
        $prodCollection = $this->productFactory->create()->getCollection();
        $collectionSize = $prodCollection->getSize();
        $firstItemType = $prodCollection->getFirstItem()->getTypeId();
        $finalPrice = $prodCollection->getFirstItem()->getFinalPrice();

        $prodCollection->addAttributeToSelect('*');
        $prodCollection->addAttributeToFilter('sku', ['eq'=>$sku]);
        $prodCollection->addAttributeToFilter('type_id', ['neq'=>'bundle']);

        if ($collectionSize == 1 && $firstItemType !="grouped") {
            return $finalPrice;
        } else {
            return 1;
        }
    }

    /**
     * Get "Product price" by ProductId
     *
     * @return int
     */		
    public function getDealproductbyId($productId)
    {
        $productcollection = $this->productFactory->create()
			->getCollection();
        $productcollection->addAttributeToSelect('*');
        $productcollection->addAttributeToFilter(
			'entity_id', ['eq'=>$productId]
		);
        $sku = $productcollection->getFirstItem()->getSku();
        
        return $this->getDealProductPrice($sku);
    }
    
    /**
     * Get Current Date
     *
     * @return string
     */		
    public function getcurrentDate()
    {
         return $this->dateTime->gmtDate("Y-m-d H:i:s");
    }
	
    /**
     * Get Current Currency Symbol
     *
     * @return string
     */		
    public function getcurrencySymbol()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()
			->getCurrencySymbol();
    }

    /**
     * Get Daily deal Collection
     *
     * @return array
     */	  
    public function getDailydealcollection()
    {
        $dailydealcollection = $this->dailydealFactory->create()
			->getCollection();
			
        return $dailydealcollection;
    }    
 
    /**
     * Get Dailydeal Product with Discount Price
     *
     * @return void
     */		
    public function getDealProductPrice($dealproductsku)
    {
        $dailydealcollection=$this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq'=>$dealproductsku]
		);
        
        return $dailydealcollection->getFirstItem()
			->getUmProductPrice();
    }
    
    /**
     * Get Discount Value  of Dailydeal Product
     *
     * @return int
     */		
    public function getDealProductDiscountValue($dealproductsku)
    {
        $dailydealcollection = $this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq'=>$dealproductsku]
		);
        
        return $dailydealcollection->getFirstItem()
			->getUmDiscountAmount();
    }
	
    /**
     * Get Dailydeal Product TO date
     *
     * @return void
     */	
    public function getDailydealToDate($dealproductsku)
    {
        $dailydealcollection=$this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq'=>$dealproductsku]
		);
        
        return $dailydealcollection->getFirstItem()
			->getUmDateTo();
    }	
         
    /**
     * Get "OFF value" (in percentage) of Dailydeal Product
     *
     * @return void
     */		
    public function getDealOffValue($dealproductsku)
    {
        $dailydealcollection=$this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter('um_product_sku', ['eq'=>$dealproductsku]);
        
        $discountType = $dailydealcollection->getFirstItem()->getUmDiscountType();
        if ($discountType == 1) {			
			$productPrice = $this->getProductPrice($dealproductsku);
			$dealProductPrice = $this->getDealProductPrice($dealproductsku);
            $off = (($productPrice - $dealProductPrice)* 100)/  $productPrice;
            return $off;
        } elseif ($discountType == 2) {
            return $dailydealcollection->getFirstItem()->getUmDiscountAmount();
        }
    }
    
    /**
     * Get Dailydeal Product FROM Date
     *
     * @return void
     */		
    public function getDailydealFromDate($dealproductsku)
    {
        $dailydealcollection=$this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq'=>$dealproductsku]
		);
        
        return $dailydealcollection->getFirstItem()
			->getUmDateFrom();
    }
	
    /**
     * Get "Save value" (In price) of dailydeal Product
     *
     * @return void
     */		
    public function getDealSaveValue($dealproductsku)
    {
        $dailydealcollection = $this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter('um_product_sku', ['eq'=>$dealproductsku]);
        
        $discountType = $dailydealcollection->getFirstItem()->getUmDiscountType();
        if ($discountType ==1) {
            return $dailydealcollection->getFirstItem()->getUmDiscountAmount();
        } elseif ($discountType ==2) {
            $save = $this->getProductPrice($dealproductsku) - $this->getDealProductPrice($dealproductsku);
            return $save;
        }
    }

    /**
     * Get Um discount type of dailydeal Product
     *
     * @return void
     */		
    public function getProductUmDiscountType($dealproductsku)
    {
        $dailydealcollection=$this->getDailydealcollection();
        $dailydealcollection->addFieldToSelect('*');
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq'=>$dealproductsku]
		); 
		
        $discountType = $dailydealcollection->getFirstItem()
			->getUmDiscountType();
		
        return $discountType;
    }
}
