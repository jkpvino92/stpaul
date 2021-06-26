<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Ulmod\Dailydeals\Block\Pricing\Render;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Ulmod\Dailydeals\Model\Config as ConfigData;
use Magento\Catalog\Pricing\Price;
use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Msrp\Pricing\Price\MsrpPrice;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;

class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    /**
     * @var ConfigData
     */
    public $configData; 
    
    /**
     * @var SalableResolverInterface
     */
    private $salableResolver;
    /**
     * @var MinimalPriceCalculatorInterface
     */
    private $minimalPriceCalculator;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param array $data
     * @param SalableResolverInterface $salableResolver
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     */
    public function __construct(
        Context $context,
        ConfigData $configData,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null
    ) {
        $this->configData = $configData;       
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data);
        $this->salableResolver = $salableResolver ?: ObjectManager::getInstance()->get(SalableResolverInterface::class);
        $this->minimalPriceCalculator = $minimalPriceCalculator
            ?: ObjectManager::getInstance()->get(MinimalPriceCalculatorInterface::class);
    }

    /**
     * @return ConfigData
     */	
    public function getConfigData()
    {
        return $this->configData;
    }
}
