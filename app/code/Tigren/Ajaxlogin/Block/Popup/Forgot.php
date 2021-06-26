<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Popup;

/**
 * Class Forgot
 * @package Tigren\Ajaxlogin\Block\Popup
 */
class Forgot extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Tigren\Ajaxlogin\Helper\Data
     */
    protected $_ajaxLoginHelper;

    /**
     * Forgot constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Tigren\Ajaxlogin\Helper\Data $ajaxLoginHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tigren\Ajaxlogin\Helper\Data $ajaxLoginHelper,
        array $data = []
    ) {
        $this->_ajaxLoginHelper = $ajaxLoginHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->_ajaxLoginHelper->getForgotPasswordPopupHtml();
    }

}