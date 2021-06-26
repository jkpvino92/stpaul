<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ulmod\Dailydeals\Model\Dailydeal\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;

class UmDealProduct implements ArrayInterface
{
    /**
     * @return int
     */    
    const FIXED = 1;
    const PERCENTAGE = 2;

    /**
     * to option array
     *
     * @return array
     */
    protected $productFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;    
    
    /**
     * @var Configurable
     */
    protected $configurable;
	
    /**
     * @param StoreManagerInterface $storeManager	 
     * @param Configurable $configurable
     * @param ProductFactory $productFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Configurable $configurable,		
        ProductFactory $productFactory
    ) {    
        $this->productFactory = $productFactory;
        $this->configurable = $configurable;		
        $this->storeManager = $storeManager;		
    }

    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $childArray=[0];

        $currencysymbol = $this->storeManager->getStore()
            ->getCurrentCurrency()
            ->getCurrencySymbol();
        
        $productcollection = $this->productFactory->create()
            ->getCollection();
			
        $productcollection->addAttributeToSelect('*');
        $productcollection->addAttributeToFilter(
            'entity_id', ['nin'=>$childArray]
        );

		// filter enabled products
		$productcollection->addAttributeToFilter(
			'status',
			\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
		);		

        $options = [
            'value'=>'',
            'label'=>'-- Select Product --'
        ];
        
        foreach ($productcollection as $product) {

            // this is child product id 
            $productId = $product->getId();  

			$parentIdsByChild = $this->configurable->getParentIdsByChild($productId);
            if (isset($parentIdsByChild[0])) {
                $prodCollection = $this->productFactory->create()
                    ->getCollection();

                $prodCollection->addFieldToSelect('*');

                $prodCollection->addFieldToFilter(
                    'entity_id', ['eq' => $parentIdsByChild[0]]
                );
              
                $name = $prodCollection->getFirstItem()->getName();                
                $price = $product->getFinalPrice();
                $sku = $prodCollection->getFirstItem()->getSku();
                $id = $prodCollection->getFirstItem()->getId();

            } else {
                if ($product->getTypeId() == "bundle") {

                    $bundleprice = [];
                    $name = $product->getName();
                    $sku = $product->getSku();                   
                    $id = $product->getId();
                   
                    $bundleProd = $this->productFactory->create()
                        ->load($product->getId());

                    $bundleOptionsIds = $bundleProd->getTypeInstance(true)
                        ->getOptionsIds($bundleProd);  

                    //get all the selection products used in bundle product.
                    $selectionCollection = $bundleProd->getTypeInstance(true)
                        ->getSelectionsCollection(
                            $bundleOptionsIds,
                            $bundleProd
                        );                   
                    
                    foreach ($selectionCollection as $productSelection) {
                        array_push($bundleprice, $productSelection->getFinalPrice());
                    }

                    $price = min($bundleprice);

                } elseif ($product->getTypeId() == "grouped") {
                    $groupedprice = [];
                    $groupedProd = $this->productFactory->create()
                        ->load($product->getId());

                     $associatedProducts = $groupedProd->getTypeInstance()
                        ->getAssociatedProducts($groupedProd);

                    foreach ($associatedProducts as $_item) {
                        array_push($groupedprice, $_item->getFinalPrice());
                    }                          

                    $sku = $product->getSku();
                    $id = $product->getId();
                    $name = $product->getName();                    
                    $price = min($groupedprice);

                } elseif ($product->getvisibility() !=1) {
                    $name = $product->getName();                    
                    $sku = $product->getSku();
                    $id = $product->getId();
                    $price = $product->getFinalPrice();
                }
            }
            
            if ($price != 0) {
                $options[] =
                [ 'value'=>$sku,
                'label'=>"ID:".$id." - ".$sku." - ".$name."- ".$currencysymbol."".round($price, 2)." "
                ];
            }
        }

        $unique = array_map(
            "unserialize", 
            array_unique(array_map("serialize", $options))
        );

        return $unique;
    }
}
