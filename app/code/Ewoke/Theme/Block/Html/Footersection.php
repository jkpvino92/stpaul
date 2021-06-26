<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ewoke\Theme\Block\Html;

use Magento\Framework\View\Element\Template;

/**
 * Html page welcome block
 *
 * @api
 * @since 100.0.2
 */
class Footersection extends Template
{
    /**
     * Get block message
     *
     * @return string
     */
    protected $_categoryCollectionFactory;

    protected $_categoryHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        parent::__construct($context, $data);
    }


    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false) {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // set pagination
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

        return $collection;
    }

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true) {
        return $this->_categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }

}
