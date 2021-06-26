<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Controller\Adminhtml\Dailydeal;

use Magento\Framework\Controller\Result\JsonFactory;
use Ulmod\Dailydeals\Model\DailydealFactory;
use Magento\Backend\App\Action\Context;     
use Magento\Framework\Exception\LocalizedException;
use Ulmod\Dailydeals\Model\Dailydeal as DealModel;

abstract class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * JSON Factory
     *
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Dailydeal Factory
     *
     * @var DailydealFactory
     */
    protected $dealFactory;

    /**
     * constructor
     *
     * @param JsonFactory $jsonFactory
     * @param DailydealFactory $dealFactory
     * @param Context $context
     */
    public function __construct(
        JsonFactory $jsonFactory,
        DailydealFactory $dealFactory,
        Context $context
    ) {
    
        $this->jsonFactory      = $jsonFactory;
        $this->dailydealFactory = $dealFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();

        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        $ajaxParam = $this->getRequest()->getParam('isAjax');

        if (!($ajaxParam && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        
        foreach (array_keys($postItems) as $dealId) {
            
            /** @var DealModel $deal */
            $deal = $this->dailydealFactory->create()->load($dealId);
            try {
                $dealData = $postItems[$dealId]; //todo: handle dates
                $deal->addData($dealData);
                $deal->save();
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithDailydealId(
                    $deal, $e->getMessage()
                );
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithDailydealId(
                    $deal, $e->getMessage()
                );
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithDailydealId(
                    $deal,
                    __('Something went wrong while saving the Dailydeal.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Dailydeal id to error message
     *
     * @param DealModel $deal
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithDailydealId(DealModel $deal, $errorText)
    {
        return '[Dailydeal ID: ' . $deal->getId() . '] ' . $errorText;
    }
}
