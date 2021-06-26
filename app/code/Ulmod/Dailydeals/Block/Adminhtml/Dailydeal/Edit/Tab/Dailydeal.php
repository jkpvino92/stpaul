<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Adminhtml\Dailydeal\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Source\Yesno as SourceYesno;
use Ulmod\Dailydeals\Model\Dailydeal\Source\UmDiscountType;
use Ulmod\Dailydeals\Model\Dailydeal\Source\UmDealProduct;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;		
		
class Dailydeal extends Generic implements TabInterface
{
    /**
     * @var SourceYesno
     */
    protected $booleanOptions;

    /**
     * @var UmDiscountType
     */
    protected $umDiscountTypeOptions;
    /**
	
     * @var UmDealProduct
     */
    protected $umDealProductOptions;
    /**
     * constructor
     *
     * @param SourceYesno $booleanOptions
     * @param UmDiscountType $umDiscountTypeOptions
     * @param UmDealProduct $umDealProductOptions
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        SourceYesno $booleanOptions,
        UmDiscountType $umDiscountTypeOptions,
        UmDealProduct $umDealProductOptions,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        array $data = []
    ) {    
        $this->booleanOptions         = $booleanOptions;
        $this->umDiscountTypeOptions  = $umDiscountTypeOptions;
        $this->umDealProductOptions   = $umDealProductOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Ulmod\Dailydeals\Model\Dailydeal $dailydeal */
        $dailydeal = $this->_coreRegistry->registry('um_dailydeals_dailydeal');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('dailydeal_');
        $form->setFieldNameSuffix('dailydeal');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('General'),
                'class'  => 'fieldset-wide'
            ]
        );
        if ($dailydeal->getId()) {
            $fieldset->addField(
                'dailydeal_id',
                'hidden',
                ['name' => 'dailydeal_id']
            );
        }
        $fieldset->addField(
            'um_product_sku',
            'select',
            [
                'name'  => 'um_product_sku',
                'label' => __('Product'),
                'title' => __('Product'),
                'onchange' => 'checkSelectedItem(this.value)',
                'values' => array_merge(['' => ''], $this->umDealProductOptions->toOptionArray()),
            ]
        );

        $fieldset->addField(
            'um_deal_enable',
            'select',
            [
                'name'  => 'um_deal_enable',
                'label' => __('Enable'),
                'title' => __('Enable'),
                'values' => $this->booleanOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'um_discount_type',
            'select',
            [
                'name'  => 'um_discount_type',
                'label' => __('Discount Type'),
                'title' => __('Discount Type'),
                'values' => array_merge(['' => ''], $this->umDiscountTypeOptions->toOptionArray()),
            ]
        );
        $fieldset->addField(
            'um_discount_amount',
            'text',
            [
                'name'  => 'um_discount_amount',
                'label' => __('Discount Value'),
                'title' => __('Discount Value'),
            ]
        );
        $fieldset->addField(
            'um_date_from',
            'date',
            [
                'name'  => 'um_date_from',
                'label' => __('Date From'),
                'title' => __('Date From'),
                'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
                'time_format' => $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT),
        
                'class' => 'validate-date',
            ]
        );
        $fieldset->addField(
            'um_date_to',
            'date',
            [
                'name'  => 'um_date_to',
                'label' => __('Date To'),
                'title' => __('Date To'),
                'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
                'time_format' => $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT),
                'class' => 'validate-date',
            ]
        );

        $dailydealData = $this->_session->getData('um_dailydeals_dailydeal_data', true);
        if ($dailydealData) {
            $dailydeal->addData($dailydealData);
        } else {
            if (!$dailydeal->getId()) {
                $dailydeal->addData($dailydeal->getDefaultValues());
            }
        }
        $form->addValues($dailydeal->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
