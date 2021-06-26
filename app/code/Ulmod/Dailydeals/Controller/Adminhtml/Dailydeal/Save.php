<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Stdlib\DateTime\Filter\Date as FilterDate;
use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ProductFactory;		
use Magento\Backend\Model\Session as ModelSession;
		
class Save extends \Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal
{
    /**
     * @var ModelSession
     */
    protected $backendSession;

    /**
     * @var FilterDate
     */
    protected $dateFilter;
	
    /**
     * @var FilterDate
     */
    protected $productFactory;
	
    /**
     * constructor
     *
     * @param ModelSession $backendSession
     * @param FilterDate $dateFilter
     * @param DailydealFactory $dailydealFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        Context $context,	
        FilterDate $dateFilter,
        DailydealFactory $dailydealFactory,
        Registry $registry,
        ProductFactory $productFactory
    ) {    
        parent::__construct($dailydealFactory, $registry, $context);
        $this->backendSession = $context->getSession();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->productFactory = $productFactory;
        $this->dateFilter     = $dateFilter;
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('dailydeal');
        if (isset($data["dailydeal_id"])) {
            $dailydealId=$data["dailydeal_id"];
        }
		
        // Store the date from and to in to varaible
        $fromdate=$data["um_date_from"];
        $todate= $data["um_date_to"];

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->filterData($data);
           
            $dailydeal = $this->initDailydeal();
            $dailydeal->setData($data);
            
            $this->_eventManager->dispatch(
                'um_dailydeals_dailydeal_prepare_save',
                [
                    'dailydeal' => $dailydeal,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $dailydealCollection=$this->dailydealFactory->create()->getCollection();
       
                $dailydealCollection->addFieldToSelect('*');
                $dailydealCollection->addFieldToFilter('um_product_sku', ['eq'=>$data["um_product_sku"]]);
                if (isset($dailydealId)) {
                    $dailydealCollection->addFieldToFilter('dailydeal_id', ['eq'=>$dailydealId]);
                    if ($dailydealCollection->getSize()==1) {
                        $editaction=1;
                    }
                }
              
                if ($dailydealCollection->getSize()== 0 || isset($editaction)) {
                    if ($data["um_deal_enable"] == 1) {
                        $productCollection=$this->productFactory->create()->getCollection();
                        $product=$productCollection->addAttributeToSelect('*');
                        $product=$productCollection->addAttributeToFilter('sku', ['eq'=>$data["um_product_sku"]]);

                        $finalproductprice=$product->getFirstItem()->getFinalPrice();
                        if ($product->getFirstItem()->getTypeId() != "bundle") {
                            if ($data["um_discount_type"] == 1) { // For Fixed
                          
                                $dailydeal->setUmProductPrice($finalproductprice - $data["um_discount_amount"]);
                            } elseif ($data["um_discount_type"] == 2) { // For Percentage
                                $dailydeal->setUmProductPrice($finalproductprice  - (($finalproductprice * $data["um_discount_amount"])/100));
                            }
                        } else {
                            $dailydeal->setUmProductPrice(1);
                        }
                    }

                    $dailydeal->setUmDateFrom($fromdate);
                    $dailydeal->setUmDateTo($todate);

                    $dailydeal->save();
    
                    $this->messageManager->addSuccess(__('The Dailydeal has been saved.'));
                } else {
                    $this->messageManager->addError("Already set dailydeal for this Product.");
                }
 
                $this->backendSession->setUmDailydealsDailydealData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'um_dailydeals/*/edit',
                        [
                            'dailydeal_id' => $dailydeal->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('um_dailydeals/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Dailydeal.'));
            }
            $this->_getSession()->setUmDailydealsDailydealData($data);
            $resultRedirect->setPath(
                'um_dailydeals/*/edit',
                [
                    'dailydeal_id' => $dailydeal->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('um_dailydeals/*/');
        return $resultRedirect;
    }

    /**
     * filter values
     *
     * @param array $data
     * @return array
     */
    protected function filterData($data)
    {
        $inputFilter = new \Zend_Filter_Input(
            [
                'um_date_from' => $this->dateFilter,
                'um_date_to' => $this->dateFilter,
            ],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        return $data;
    }
}
