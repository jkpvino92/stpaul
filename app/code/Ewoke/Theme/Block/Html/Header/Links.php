<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ewoke\Theme\Block\Html\Header;

use Magento\Framework\View\Element\Template;


/**
 * Header Bar block
 *
 * @api
 * @since 100.0.2
 */
class Links extends \Magento\Framework\View\Element\Html\Link
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
        return '<li><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}
