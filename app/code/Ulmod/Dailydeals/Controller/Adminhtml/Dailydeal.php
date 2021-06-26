<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml;

use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;		
		
abstract class Dailydeal extends \Magento\Backend\App\Action
{
    /**
     * @var DailydealFactory
     */
    protected $dailydealFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * constructor
     *
     * @param DailydealFactory $dailydealFactory
     * @param Registry $coreRegistry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        DailydealFactory $dailydealFactory,
        Registry $coreRegistry,
        Context $context
    ) {    
        $this->dailydealFactory      = $dailydealFactory;
        $this->coreRegistry          = $coreRegistry;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    /**
     * Init Dailydeal
     *
     * @return \Ulmod\Dailydeals\Model\Dailydeal
     */
    protected function initDailydeal()
    {
        $dailydealId  = (int) $this->getRequest()->getParam('dailydeal_id');
		
        /** @var \Ulmod\Dailydeals\Model\Dailydeal $dailydeal */
        $dailydeal    = $this->dailydealFactory->create();
        if ($dailydealId) {
            $dailydeal->load($dailydealId);
        }
        $this->coreRegistry->register('um_dailydeals_dailydeal', $dailydeal);
		
        return $dailydeal;
    }
}
