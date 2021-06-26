<?php
namespace Meetanshi\PayshipRestriction\Controller\Adminhtml\PayshipRestriction;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Payment extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Meetanshi_PayshipRestriction::menu_payment');
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Meetanshi_PayshipRestriction::menu_payment');
        $resultPage->getConfig()->getTitle()->prepend(__('Payment Methods Restriction by Customer Groups'));
        $resultPage->addBreadcrumb(__('Payment Methods Restriction by Customer Groups'), __('Payment Methods Restriction by Customer Groups'));
        return $resultPage;
    }
}
