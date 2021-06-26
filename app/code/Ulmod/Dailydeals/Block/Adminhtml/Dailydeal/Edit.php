<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Adminhtml\Dailydeal;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;

class Edit extends Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * constructor
     *
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        Context $context,
        array $data = []
    ) {
    
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Dailydeal edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'dailydeal_id';
        $this->_blockGroup = 'Ulmod_Dailydeals';
        $this->_controller = 'adminhtml_dailydeal';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Dailydeal'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Dailydeal'));
    }
    /**
     * Retrieve text for header element depending on loaded Dailydeal
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Ulmod\Dailydeals\Model\Dailydeal $dailydeal */
        $dailydeal = $this->coreRegistry->registry('um_dailydeals_dailydeal');
        if ($dailydeal->getId()) {
            return __("Edit Dailydeal '%1'", $this->escapeHtml($dailydeal->getUm_product_sku()));
        }
        return __('New Dailydeal');
    }
}
