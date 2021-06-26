<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

use Magento\Ui\Component\MassAction\Filter as MassActionFilter;
use Ulmod\Dailydeals\Model\ResourceModel\Dailydeal\CollectionFactory as DailydealCollectionFactory;
use Magento\Backend\App\Action\Context;
		
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var MassActionFilter
     */
    protected $filter;

    /**
     * Collection Factory
     *
     * @var DailydealCollectionFactory
     */
    protected $collectionFactory;

    /**
     * constructor
     *
     * @param MassActionFilter $filter
     * @param DailydealCollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        MassActionFilter $filter,
        DailydealCollectionFactory $collectionFactory,
        Context $context
    ) {    
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $delete = 0;
        foreach ($collection as $item) {
            /** @var \Ulmod\Dailydeals\Model\Dailydeal $item */
            $item->delete();
            $delete++;
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));
		
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(
			\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT
		);
		
        return $resultRedirect->setPath('*/*/');
    }
}
