<?php
namespace Inchoo\CatalogWidget\Block\Product;

class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    const DEFAULT_COLLECTION_SORT_BY = 'name';
    const DEFAULT_COLLECTION_ORDER = 'asc';

    /**
     * Prepare and return product collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1))
            ->setOrder($this->getSortBy(), $this->getSortOrder());

        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        return $collection;
    }

    /**
     * Retrieve sort by
     *
     * @return int
     */
    public function getSortBy()
    {
        if (!$this->hasData('collection_sort_by')) {
            $this->setData('collection_sort_by', self::DEFAULT_COLLECTION_SORT_BY);
        }
        return $this->getData('collection_sort_by');
    }

    /**
     * Retrieve sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        if (!$this->hasData('collection_sort_order')) {
            $this->setData('collection_sort_order', self::DEFAULT_COLLECTION_ORDER);
        }
        return $this->getData('collection_sort_order');
    }


    public function getValue($var, $return){
        $p = '';
        $var2 = '';

        while (strlen($var) > 0) {
            if($p = strpos($var, 'value')){
                $var2= substr($var, $p+8);
                $p2 = strpos($var2, '`');
                $return[] = substr($var2, 0,$p2);
                $var = substr($var2, $p2);
            }else{
                $var = '';
            }
        }
        return $return;
    }



    public function createCategoryIds()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */

        if (!$this->hasData('parent_category')) {
            $this->setData('parent_category');
        }
        if (!$this->hasData('conditions_encoded')) {
            $this->setData('conditions_encoded');
        }


        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $category = $_objectManager->create('Magento\Catalog\Model\Category')
            ->load($this->getData('parent_category'));

        //return $category->getUrl();
        $conditions_value = $this->getData('conditions_encoded');
        $value = $this->getValue($conditions_value, array());

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $serializer = $objectManager->create(\Magento\Framework\Serialize\SerializerInterface::class);


        if ($this->getData('parent_category') == $value[1]) {
            return ' <a href="'.$category->getUrl().'" class="view-all">View All</a>';
        } else {

            return ' <a href="javascript:;" onclick="alert(\'Mismatching category ID!!!\');" class="view-all">View All</a>';
        }

        //return $this->getData('parent_category');

        //return  $visibility;

    }



}
