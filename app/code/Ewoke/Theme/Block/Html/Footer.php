<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ewoke\Theme\Block\Html;

use Magento\Customer\Model\Context;

/**
 * Html page footer block
 *
 * @api
 * @since 100.0.2
 */
class Footer extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $logo;
    /**
     * Copyright information
     *
     * @var string
     */
    protected $_copyright;

    /**
     * Miscellaneous HTML information
     *
     * @var string
     */
    private $miscellaneousHtml;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    protected $info;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Framework\App\Config\ScopeConfigInterface $info,
        array $data = []
    ) {
        $this->httpContext = $httpContext;
        $this->_logo = $logo;
        $this->info = $info;
        parent::__construct($context, $data);

    }

    /**
     * Set footer data
     *
     * @return void
     */
    protected function _construct()
    {
        $this->addData(
            [
                'cache_tags' => [\Magento\Store\Model\Store::CACHE_TAG, \Magento\Cms\Model\Block::CACHE_TAG],
            ]
        );
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'PAGE_FOOTER',
            $this->_storeManager->getStore()->getId(),
            (int)$this->_storeManager->getStore()->isCurrentlySecure(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(Context::CONTEXT_AUTH),
            $this->getTemplateFile(),
            'template' => $this->getTemplate()
        ];
    }

    /**
     * Retrieve copyright information
     *
     * @return string
     */
    public function getCopyright()
    {
        if (!$this->_copyright) {
            $this->_copyright = $this->_scopeConfig->getValue(
                'design/footer/copyright',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return __($this->_copyright);
    }

    /**
     * Retrieve Miscellaneous HTML information
     *
     * @return string
     * @since 100.1.0
     */
    public function getMiscellaneousHtml()
    {
        if ($this->miscellaneousHtml === null) {
            $this->miscellaneousHtml = $this->_scopeConfig->getValue(
                'design/footer/absolute_footer',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }
        return $this->miscellaneousHtml;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Store\Model\Store::CACHE_TAG, \Magento\Cms\Model\Block::CACHE_TAG];
    }

    /**
     * Get block cache life time
     *
     * @return int
     */
    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 3600;
    }

    /**
     * Get logo image URL
     *
     * @return string
     */
    public function getLogoSrc()
    {
        return $this->_logo->getLogoSrc();
    }

    /**
     * Get logo text
     *
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->_logo->getLogoAlt();
    }


    public function getStoreName()
    {
        return $this->_scopeConfig->getValue(
                'general/store_information/name',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }

    public function getStoreStreet1()
    {
        return $this->_scopeConfig->getValue(
                'general/store_information/street_line1',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }

    public function getStoreStreet2()
    {
        return $this->_scopeConfig->getValue(
                'general/store_information/street_line2',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }


    public function getStoreEmail()
    {
        return $this->_scopeConfig->getValue(
                'trans_email/ident_general/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }
}
