<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Ulmod\Dailydeals\Model\DailydealFactory as ModelDailydealFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session as ModelSession;
	
class Edit extends \Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal
{
    /**
     * Backend session
     *
     * @var ModelSession
     */
    protected $backendSession;

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result JSON factory
     *
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * constructor
     *
     * @param ModelSession $backendSession
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param ModelDailydealFactory $dailydealFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ModelDailydealFactory $dailydealFactory,
        Registry $registry,
        Context $context
    ) {    
        $this->backendSession    = $context->getSession();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($dailydealFactory, $registry, $context);
    }

    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ulmod_Dailydeals::dailydeal');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     *	|\Magento\Backend\Model\View\Result\Redirect
     *  |\Magento\Framework\View\Result\Page	 
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('dailydeal_id');
		
        /** @var \Ulmod\Dailydeals\Model\Dailydeal $dailydeal */
        $dailydeal = $this->initDailydeal();
		
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ulmod_Dailydeals::dailydeal');
        $resultPage->getConfig()->getTitle()->set(__('Dailydeals'));
        if ($id) {
            $dailydeal->load($id);
            if (!$dailydeal->getId()) {
                $this->messageManager->addError(__('This Dailydeal no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'um_dailydeals/*/edit',
                    [
                        'dailydeal_id' => $dailydeal->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $dailydeal->getId() ? $dailydeal->getUm_product_sku() : __('New Dailydeal');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->backendSession->getData('um_dailydeals_dailydeal_data', true);
        if (!empty($data)) {
            $dailydeal->setData($data);
        }
        return $resultPage;
    }
}
