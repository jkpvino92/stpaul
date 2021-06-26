<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
		
class Index extends \Magento\Backend\App\Action
{
    /**
     * Page result factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Page factory
     *
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $resultPage;

    /**
     * constructor
     *
     * @param PageFactory $resultPageFactory
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Context $context
    ) {
    
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->setPageData();
        return $this->getResultPage();
    }
	
    /**
     * instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if (is_null($this->resultPage)) {
            $this->resultPage = $this->resultPageFactory->create();
        }
        return $this->resultPage;
    }
	
    /**
     * set page data
     *
     * @return $this
     */
    protected function setPageData()
    {
        $resultPage = $this->getResultPage();
		
        // $resultPage->setActiveMenu('Ulmod_Dailydeals::dailydeal');
        $resultPage->getConfig()->getTitle()->prepend((__('Dailydeals')));
        return $this;
    }
}
