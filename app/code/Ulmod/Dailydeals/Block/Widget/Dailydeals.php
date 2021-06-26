<?php
/** Copyright © Ulmod. All rights reserved. See LICENSE.txt for license details. */

namespace Ulmod\Dailydeals\Block\Widget;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductsCollectionFactory;
use Magento\Catalog\Model\Product\Visibility as CatalogProductVisibility;
use Magento\Framework\Stdlib\DateTime\DateTime as StdlibDateTime;
use Magento\Reports\Model\Event\TypeFactory as EventTypeFactory;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Catalog\Model\ProductFactory;
use Ulmod\Dailydeals\Model\Config as ConfigModel;
use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Helper\Output as OutputHelper;

/**
 * Dailydeals block
 */
class Dailydeals extends \Magento\Catalog\Block\Product\AbstractProduct implements BlockInterface
{
	protected $_template = 'widget/list.phtml';
	
    /**
     * @var DailydealFactory
     */		
    protected $dailydealFactory;

    /**
     * @var ConfigModel
     */		
    protected $configModel;

    /**
     * Render
     */
    protected $renderer;

    /**
     * Events type factory
     *
     * @var EventTypeFactory
     */
    protected $eventTypeFactory;
    
    /**
     * @var CatalogProductVisibility
     */
    protected $catalogProductVisibility;
    
    /**
     * @var ProductsCollectionFactory
     */
    protected $productsCollectionFactory;
    
    /**
     * @var StdlibDateTime
     */
    protected $stdlibDateTime;
    
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;
    
    /**
     * @var ProductFactory
     */	
    protected $productFactory;
    
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
        ProductsCollectionFactory $productsCollectionFactory,
        ProductFactory $productFactory,
        CatalogProductVisibility $catalogProductVisibility,
        StdlibDateTime $stdlibDateTime,
        EventTypeFactory $eventTypeFactory,
        UrlHelper $urlHelper,
        ConfigModel $configModel,
        DailydealFactory $dailydealFactory, 
        OutputHelper $outputHelper,		
        array $data = []

    ) {    
        $this->productFactory = $productFactory;
        $this->dailydealFactory = $dailydealFactory;
        $this->configModel = $configModel; 
        $this->productCollectionFactory = $productsCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->stdlibDateTime = $stdlibDateTime;
        $this->eventTypeFactory = $eventTypeFactory;
        $this->storeManager = $context->getStoreManager();
        $this->urlHelper = $urlHelper;
        $this->outputHelper = $outputHelper;		
        parent::__construct($context, $data);
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
        $collection	= $this->getDailydealCollection();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter('um_deal_enable', ['eq' => 1]);
        $collection->setPageSize($this->getData('items_number'));
 
        return $collection;
    }
	
    /**
     * Retrieve daily deals product collection
     *
     * @return array
     */	
    public function getDailydealCollection()
    {
        $collection	= $this->dailydealFactory->create()
			->getCollection();
			
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
        $productCollection->addAttributeToFilter('sku', ['eq'=>$productSku]);
        
        return $productCollection;
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
        $dailydealcollection->addFieldToFilter('um_product_sku', ['eq'=>$productSku]);
        if ($dailydealcollection->getSize() == 1) {        
            $curdate = strtotime($this->stdlibDateTime->gmtDate("Y-m-d H:i:s"));
            $Todate = strtotime($dailydealcollection->getFirstItem()->getUmDateTo());
            $fromdate = strtotime($dailydealcollection->getFirstItem()->getUmDateFrom());
            
            // calculate two days time
            $twodays_duration = 172800;            
            $expiredduration = $curdate-$Todate; // It returns positive value                
            $comingsoonduration = $curdate-$fromdate; // It returns Negative value
             
            // Check datetime duration before two days and two days after
            if ($expiredduration > $twodays_duration || $comingsoonduration < -$twodays_duration) {
                return false;
            } else {
                return true; // Return True if collection of product which expired and comming is two days duration
            }
        } else {
            return false;
        }
    }
}
