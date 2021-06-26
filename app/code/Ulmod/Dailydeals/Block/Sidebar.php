<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ulmod\Dailydeals\Block;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Ulmod\Dailydeals\Model\Config as ConfigModel;
use Ulmod\Dailydeals\Model\DailydealFactory;		
use Magento\Framework\Stdlib\DateTime\DateTime as StdlibDateTime;
use Magento\Catalog\Helper\Output as OutputHelper;

/**
 * Sidebar block
 */
class Sidebar extends ListProduct
{
    /**
     * @var ProductFactory
     */	
    protected $productFactory;

    /**
     * @var ConfigModel
     */		
    protected $configModel;

    /**
     * @var DailydealFactory
     */     
    protected $dailydealFactory;    
	
    /**
     * @var Magento\Framework\App\Config\ScopeConfigInterface
     */ 
    protected $scopeConfig;

    /**
     * @var StdlibDateTime
     */		
    protected $stdlibDateTime;	
 
    /**
     * @var OutputHelper
     */
    protected $outputHelper; 
   
    /**
     * @param  Context $context
     * @param  ProductFactory $productFactory
     * @param  PostHelper $postDataHelper
     * @param  LayerResolver $layerResolver
     * @param  CategoryRepositoryInterface $categoryRepository
     * @param  UrlHelper $urlHelper
     * @param  ConfigModel $configModel
     * @param  DailydealFactory $dailydealFactory
     * @param  StdlibDateTime $stdlibDateTime 
     * @param  array $data
     */	   
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        PostHelper $postDataHelper,
        ConfigModel $configModel,
        DailydealFactory $dailydealFactory,
        LayerResolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        UrlHelper $urlHelper,        
        StdlibDateTime $stdlibDateTime,	
        OutputHelper $outputHelper,	
        array $data = []
    ) {    
        $this->productFactory = $productFactory;
        $this->stdlibDateTime = $stdlibDateTime;         
        $this->configModel = $configModel;   
        $this->dailydealFactory = $dailydealFactory; 		
        $this->scopeConfig = $context->getScopeConfig();    
        $this->outputHelper = $outputHelper;		
        return parent::__construct(
			$context, 
			$postDataHelper, 
			$layerResolver, 
			$categoryRepository, 
			$urlHelper, 
			$data
		);
    }

    /**
     * @return OutputHelper
     */	
    public function getOutputHelper()
    {
        return $this->outputHelper;
    }
		
    /**
     * @return ConfigModel
     */	
    public function getConfigModel()
    {
        return $this->configModel;
    }
	
    /**
     * Retrieve daily deals product collection whose status is enabled
     *
     * @return array
     */	
    public function getDailydealEnableProduct()
    {
        $sidebarItemsNumber = $this->configModel
            ->getSidebarItemsNumber();  

        $collection	= $this->getDailydealCollection();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('um_deal_enable', ['eq' => 1]);
        $collection->setPageSize($sidebarItemsNumber);
 
        return $collection;
    }

    /**
     * Get Product Data which is common in DailydealCollection
     *
     * @return array
     */		
    public function getDailyDealProduct($productSku)
    {
        $productCollection = $this->productFactory->create()
			->getCollection();

        $productCollection->addAttributeToSelect('*');

        $productCollection->addAttributeToFilter(
            'sku', ['eq'=>$productSku]
        );
        
        return $productCollection;
    }

    
    /**
     * Retrieve daily deals product collection
     *
     * @return array
     */ 
    public function getDailydealCollection()
    {
        $collection = $this->dailydealFactory->create()
            ->getCollection();

        return $collection;
    }

    /**
     * Retrieve product collection which will expire in 2 days
     * and comming soon in two days
     *  
     * @return array
     */		
    public function recentlyDailydeal($productSku)
    {       
        $dailydealcollection = $this->getDailydealCollection();
        $dailydealcollection->addFieldToSelect('*');

        $dailydealcollection->addFieldToFilter(
            'um_product_sku', ['eq'=>$productSku]
        );
        
        $gmtDate = $this->stdlibDateTime->gmtDate("Y-m-d H:i:s");  
        $umDateTo = $dailydealcollection->getFirstItem()->getUmDateTo(); 
        $umDateFrom = $dailydealcollection->getFirstItem()->getUmDateFrom();                     
        if ($dailydealcollection->getSize() == 1) {        
            $curdate = strtotime($gmtDate);
            $Todate = strtotime($umDateTo);
            $fromdate = strtotime($umDateFrom);
            
            // calculate two days time
            $twodays_duration = 172800;            
            $expiredduration = $curdate - $Todate; // It returns positive value                
            $comingsoonduration = $curdate - $fromdate; // It returns Negative value
             
            // Check datetime duration before two days and two days after
            if ($expiredduration > $twodays_duration 
                || $comingsoonduration < -$twodays_duration) {
                return false;
            } else {
                // Return True if collection of product which expired
                // and comming is two days duration                
                return true; 
            }
        } else {
            return false;
        }
    }
}
