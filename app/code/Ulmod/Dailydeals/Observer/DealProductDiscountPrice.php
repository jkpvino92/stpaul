<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\ScopeInterface;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
		
class DealProductDiscountPrice implements ObserverInterface
{
    /**
     * @var DailydealFactory
     */	
    protected $dailydealFactory;

    /**
     * @var ScopeConfigInterface
     */		
    protected $scopeConfig;

    /**
     * @var GroupedType
     */
    protected $groupedType;	

    /**
     * @var ProductFactory
     */
    protected $productFactory;	

    /**
     * @var DateTime
     */
    protected $dateTime;		
	
    /**
     * @param DailydealFactory $dailydealFactory
     * @param GroupedType $groupedType
     * @param ProductFactory $productFactory	
     * @param DateTime $dateTime		
     * @param ScopeConfigInterface $scopeConfig
     */	
    public function __construct(
        DailydealFactory $dailydealFactory,
		GroupedType $groupedType,
		ProductFactory $productFactory,		
		DateTime $dateTime,			
        ScopeConfigInterface $scopeConfig
    ) {
        $this->dailydealFactory = $dailydealFactory;
        $this->scopeConfig = $scopeConfig;
        $this->groupedType = $groupedType;	
        $this->productFactory = $productFactory;	
        $this->dateTime = $dateTime;			
    }
	
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item ); 
		
        $cartproduct_id = $item->getProductId();    
		$product = $this->groupedType->getParentIdsByChild($item->getProduct()->getId());
        if (isset($product[0])) {
			$product = $this->productFactory->create()->load($product[0]);			
            $groupedProductFalg = 1;
            $groupedProduct = $product->getId();
        } else {
			$product = $this->productFactory->create()
				->load($item->getProduct()->getId());				
        }
       
        $dailydealcollection = $this->dailydealFactory->create()
			->getCollection();
			
        $dailydealcollection->addFieldToSelect('*');
		
        $dailydealcollection->addFieldToFilter(
			'um_product_sku', ['eq' => $product->getSku()]
		);
		
        $curdate = strtotime($this->dateTime->gmtDate("Y-m-d H:i:s"));
        $Todate = strtotime($dailydealcollection->getFirstItem()->getUmDateTo());
        $fromdate = strtotime($dailydealcollection->getFirstItem()->getUmDateFrom());
        
        $storeScope = ScopeInterface::SCOPE_STORE;        
        $configPath = "um_dailydeal/general/is_enabled";

        $isDailydealEnabled = $this->scopeConfig->getValue($configPath, $storeScope);        
        if ($isDailydealEnabled == 1 && $curdate <= $Todate && $curdate >= $fromdate) {
            if ($item->getProduct()->getTypeId() == "bundle") {
                $bundleItemPrice = [];
                $options = $item->getProduct()->getTypeInstance(true)
					->getOrderOptions($item->getProduct());
                
                foreach ($options['bundle_options'] as $bundleitems) {
                    foreach ($bundleitems['value'] as $sub) {
                        array_push($bundleItemPrice, $sub['price']);
                    }
                }

                $allItems = $item->getQuote()->getAllItems();
                foreach ($allItems as $bundleitems) {
	
                    $itemFinalPrice = $bundleitems->getProduct()->getFinalPrice();
                    $itemPrice = $bundleitems->getProduct()->getPrice();
                    $umDiscountType = $dailydealcollection->getFirstItem()->getUmDiscountType();
                    $umDiscountAmount = $dailydealcollection->getFirstItem()->getUmDiscountAmount();                    

                    /** @var $bundleitems\Magento\Quote\Model\Quote\Item */
                    if (max($bundleItemPrice) == $itemPrice) {
                        if ($umDiscountType == 1) {
                            $finalprice = $itemFinalPrice-$umDiscountAmount;
                        } elseif ($umDiscountType == 2) {
                            $finalprice = $itemFinalPrice-(($item->getProduct()->getFinalPrice()*$umDiscountAmount)/100);
                        }
                                
                        $bundleitems->setCustomPrice($finalprice);
                        $bundleitems->setOriginalCustomPrice($finalprice);
                        $item->getProduct()->setIsSuperMode(true);

                        break;
                    }
                }                    

				$item->getProduct()->setIsSuperMode(true);
            } else {
                if (isset($groupedProductFalg)) {
                    $groupedItemPrice=[];
                    foreach ($item->getQuote()->getAllItems() as $groupedItem) {
 					   $grouped_product = $this->groupedType->getParentIdsByChild(
							$groupedItem->getProduct()->getId()
						);
                        if (isset($grouped_product[0])) {
                            if ($groupedProduct == $grouped_product[0]) {
                                array_push($groupedItemPrice, $groupedItem->getProduct()->getPrice());
                            }
                        }
                    }

                    foreach ($item->getQuote()->getAllItems() as $groupedItem) {
                        $discountAmount = $dailydealcollection->getFirstItem()->getUmDiscountAmount();
                        if (min($groupedItemPrice) == $groupedItem->getProduct()->getPrice()) {
                            if ($dailydealcollection->getFirstItem()->getUmDiscountType() == 1) {
                                $price=$groupedItem->getProduct()->getPrice()-$discountAmount;
                            } elseif ($dailydealcollection->getFirstItem()->getUmDiscountType() == 2) {
                                $price = $groupedItem->getProduct()->getPrice()-(($groupedItem->getProduct()->getPrice()*$discountAmount)/100);
                            }
                            break;
                        }
                    }
                } else {
                    $price = $dailydealcollection->getFirstItem()->getUmProductPrice();
                }
                
                $item->setCustomPrice($price);
                $item->setOriginalCustomPrice($price);
                $item->getProduct()->setIsSuperMode(true);
            }
        }
    }
}
