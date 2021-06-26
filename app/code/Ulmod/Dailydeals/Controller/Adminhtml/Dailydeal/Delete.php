<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

class Delete extends \Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('dailydeal_id');
        if ($id) {
            $um_product_sku = "";
            try {
                /** @var \Ulmod\Dailydeals\Model\Dailydeal $dailydeal */
                $dailydeal = $this->dailydealFactory->create();
                $dailydeal->load($id);
                $um_product_sku = $dailydeal->getUm_product_sku();
                $dailydeal->delete();
                $this->messageManager->addSuccess(__('The Dailydeal has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_um_dailydeals_dailydeal_on_delete',
                    ['um_product_sku' => $um_product_sku, 'status' => 'success']
                );
                $resultRedirect->setPath('um_dailydeals/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_um_dailydeals_dailydeal_on_delete',
                    ['um_product_sku' => $um_product_sku, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('um_dailydeals/*/edit', ['dailydeal_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Dailydeal to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('um_dailydeals/*/');
        return $resultRedirect;
    }
}
