<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Deal;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
		
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * Path to store config of Social Share
     *
     * @var string|int
     */
    const XML_PATH_EXTENSION_ENABLED = 'um_dailydeal/general/is_enabled';
    
    /**
     * @var PageFactory
     */	
    protected $pageFactory;

    /**
     * @var RedirectFactory
     */		
    protected $resultRedirectFactory;

    /**
     * @var ScopeConfigInterface
     */		
    protected $scopeConfig;
	
    /**
     * @param Context $context,
     * @param PageFactory $pageFactory,
     * @param RedirectFactory $resultRedirectFactory,
     * @param ScopeConfigInterface $scopeConfig
     */    
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        RedirectFactory $resultRedirectFactory,
        ScopeConfigInterface $scopeConfig
    ) {    
        $this->pageFactory = $pageFactory;
        $this->resultRedirectFactory=$resultRedirectFactory;
        $this->scopeConfig=$scopeConfig;
        
        return parent::__construct($context);
    }
	
    /**
     * View daily deals
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            return $this->_forwardNoroute();
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
		
    }

    /**
     * Is the module enabled in configuration.
     *
     * @return bool
     */
    public function moduleEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EXTENSION_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Retrieve store config value
     *
     * @return string | null | bool
     */
    protected function getConfigValue($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE
        );
    }
  

    /**
     * Throw control to cms_index_noroute action.
     *
     * @return void
     */
    protected function _forwardNoroute()
    {
        $this->_forward('index', 'noroute', 'cms');
    }
}
