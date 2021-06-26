<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ewoke\Topmenu\Block\Html;



use Magento\Catalog\Model\CategoryRepository;

/**
 * Html page top menu block
 *
 * @api
 * @since 100.0.2
 */
class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    protected $categoryRepository;


    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;
        $menuId = $menuTree->getId();

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {

            $img = '';

            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

           // if ($childLevel == 1 && $this->isCategory($menuId)) {
           //     $img = '<img class="icon-left" src="'.$this->getCategoryImage($menuId).'"  alt="'.$child->getName().'"/>';
            //}


            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $category = $objectManager->create('Magento\Catalog\Model\Category')->load($this->getCategoryId($child->getId()));

//            if($category->getImageUrl()){
//                $img = '<img src="'.$category->getImageUrl().'" alt="'.$child->getName().'" class="category-icon icon-left" />';
//            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '>'.$img.'
                        <span>' . strtolower($this->escapeHtml( $child->getName() )) . '</span></a>' . $this->_addSubMenu( $child, $childLevel, $childrenWrapClass, $limit
                ) . '</li>';
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }


        return $html;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        //echo 'gireesh'; exit;
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->getMenu(), 'block' => $this, 'request' => $this->getRequest()]
        );

        $this->getMenu()->setOutermostClass($outermostClass);
        $this->getMenu()->setChildrenWrapClass($childrenWrapClass);

        $html = $this->_getHtml($this->getMenu(), $childrenWrapClass, $limit);

        $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
        );
        $html = $transportObject->getHtml();
        return $html;
    }

    ///self
    protected function getCategoryId($categoryId)
    {
        $categoryIdElements = explode('-', $categoryId);
        $catId = end($categoryIdElements);

        return $catId;
    }

    protected function isCategory($menuId)
    {
        $menuId = explode('-', $menuId);

        return 'category' == array_shift($menuId);
    }

}
