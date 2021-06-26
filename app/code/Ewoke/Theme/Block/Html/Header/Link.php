<?php
/**
 * Created by PhpStorm.
 * User: Ewoke 04
 * Date: 16-Jan-2019
 * Time: 10:45 AM
 */

namespace Ewoke\Theme\Block\Html\Header;


class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Get block message
     *
     * @return string
     */

    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a>';
    }

}