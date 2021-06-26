<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Popup;

/**
 * Class Login
 * @package Tigren\Ajaxlogin\Block\Popup
 */
class Login extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Tigren\Ajaxlogin\Helper\Data
     */
    protected $_ajaxLoginHelper;

    /**
     * Login constructor.
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
        return $this->_ajaxLoginHelper->getLoginPopupHtml();
    }

}