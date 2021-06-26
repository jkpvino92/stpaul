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
class Bar extends Template
{
    /**
     * Get block message
     *
     * @return string
     */

    protected $_storeInfo;
    protected $_storeManagerInterface;
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\Information $storeInfo,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context, $data = []);
        $this->scopeConfig = $scopeConfig;
        $this->_storeInfo = $storeInfo;
        $this->_storeManagerInterface = $storeManagerInterface;
    }


    public function getPhoneNumber()
    {
        return $this->_storeInfo->getStoreInformationObject($this->_storeManagerInterface->getStore(null))->getPhone();
    }

    public function getMail()
    {
        return $this->scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);;
    }

    public function getTopLinks()
    {
        return $this->scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);;
    }
}
