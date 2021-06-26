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
class Welcomemsg extends Template
{
    /**
     * Get block message
     *
     * @return string
     */

    public function getWelcomeMsg()
    {
        return $this->_scopeConfig->getValue(
            'design/header/welcome',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
