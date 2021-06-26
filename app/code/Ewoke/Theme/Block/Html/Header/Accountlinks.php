<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ewoke\Theme\Block\Html\Header;

use Magento\Framework\View\Element\Template;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Header Bar block
 *
 * @api
 * @since 100.0.2
 */
class Accountlinks extends Template
{
    /**
     * Get block message
     *
     * @return string
     */

    protected $_storeInfo;
    protected $_storeManagerInterface;
    protected $scopeConfig;
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\Information $storeInfo,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Customer\Model\Session $customerSession,
        ScopeConfigInterface $scopeConfig

    )
    {
        parent::__construct($context, $data = []);
        $this->scopeConfig = $scopeConfig;
        $this->_storeInfo = $storeInfo;
        $this->_storeManagerInterface = $storeManagerInterface;
        $this->customerSession = $customerSession;
    }


    public function isLoggedIn() // You can use this fucntion in any phtml file
    {
        return $this->customerSession->isLoggedIn();
    }


    public function getBaseUrl(){
        return $this->_storeManagerInterface->getStore()->getBaseUrl();
    }

    public function getCustomerData() {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomerData();
        }
        return false;
    }
}
