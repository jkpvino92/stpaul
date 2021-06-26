<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Deal;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class View extends Template
{
    /**
     * @var UrlInterface
     */
    protected $urlInterface;
 
    public function __construct(
        Context $context,        
        UrlInterface $urlInterface,    
        array $data = []
    )
    {        
        $this->urlInterface = $urlInterface;
        parent::__construct($context, $data);
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->_getConfigValue('title'));
        $this->pageConfig->setKeywords($this->_getConfigValue('meta_keywords'));
        $this->pageConfig->setDescription($this->_getConfigValue('meta_description'));
		
        $this->pageConfig->addRemotePageAsset(
            $this->urlInterface->getUrl(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
		
        return parent::_prepareLayout();
    }
	
    /**
     * Retrieve dailydeal title
     *	 
     * @return string
     */
    protected function _getConfigValue($param)
    {
        return $this->_scopeConfig->getValue(
            'um_dailydeal/index_page/'.$param,
            ScopeInterface::SCOPE_STORE
        );
    }
	
    /**
     * Prepare breadcrumbs
     *
     * @return void
     */
    protected function _addBreadcrumbs()
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()
                        ->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'dailydeals',
                [
                    'label' => __('Daily Deals'),
                    'title' => __(sprintf('Go to Daily Deals'))
                ]
            );
        }
    }

}
