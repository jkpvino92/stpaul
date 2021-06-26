<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxcompare\Block;

use Magento\Framework\View\Element\Template\Context;
use Tigren\Ajaxcompare\Helper\Data;

/**
 * Class Js
 * @package Tigren\Ajaxcompare\Block
 */
class Js extends \Magento\Framework\View\Element\Template
{

    /**
     * @var string
     */
    protected $_template = 'js/main.phtml';

    /**
     * @var Data
     */
    protected $_ajaxCompareHelper;

    /**
     * Js constructor.
     * @param Context $context
     * @param Data $ajaxCompareHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $ajaxCompareHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_ajaxCompareHelper = $ajaxCompareHelper;
    }

    /**
     * @return string
     */
    public function getAjaxCompareInitOptions()
    {
        return $this->_ajaxCompareHelper->getAjaxCompareInitOptions();
    }
}