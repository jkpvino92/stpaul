<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\AbstractElement;
		
Class ColorPicker extends Template
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
    )
    {
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
			"text", 
			['data' => $element->getData()]
		);
		
        $input->setId($element->getId());
        $input->setForm($element->getForm());
		
        $input->setClass("widget-option input-text admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }

        $imgPath = $this->getViewFileUrl('Ulmod_Dailydeals::js/color.png');
		$cssStyle =  '<style type="text/css">
		.colorpicker {z-index: 9999} .control-value {display: none !important;}
		#' . $element->getId() . ' { background-image: url('.$imgPath.') !important;
		background-position: calc(100% - 8px) center; 
		background-repeat: no-repeat; padding-right: 44px !important; }
		</style>';			
        $jsColorPicker = '<script type="text/javascript">
            require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#' . $element->getId() . '");
                    $el.css("backgroundColor", "'. $element->getEscapedValue() .'");

                    // Attach the color picker
                    $el.ColorPicker({
                        color: "'. $element->getEscapedValue() .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';
		
        $element->setData(
			'after_element_html',  
			$cssStyle . $jsColorPicker . $input->getElementHtml()
		);

        return $element;
    }
}