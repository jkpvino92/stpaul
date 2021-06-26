<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;

Class TextField extends Template
{
    /**
     * @var ElementFactory
     */	
	protected $elementFactory;
	
	/**
	 * @param Context $context
	 * @param ElementFactory $elementFactory
	 * @param array $data
	 */
	public function __construct(
		Context $context,
		ElementFactory $elementFactory,
		array $data = []
	) {
		$this->elementFactory = $elementFactory;
		parent::__construct($context, $data);
	}
	
	/**
	 * Prepare chooser element HTML
	 *
	 * @param AbstractElement $element Form Element
	 * @return AbstractElement
	 */
	public function prepareElementHtml(AbstractElement $element)
	{
		$input = $this->elementFactory->create(
			"textarea", 
			['data' => $element->getData()]
		);
		
		$input->setId($element->getId());
		$input->setForm($element->getForm());		
		$input->setClass("widget-option input-textarea admin__control-text");
		if ($element->getRequired()) {
			$input->addClass('required-entry');
		}

		$element->setData('after_element_html', $input->getElementHtml());
		
		return $element;
	}
}