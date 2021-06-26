<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Messages\Register;

use Magento\Framework\View\Element\Template;

/**
 * Class Error
 * @package Tigren\Ajaxlogin\Block\Messages\Register
 */
class Error extends Template
{
    /**
     * Error constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getForgotPasswordUrl()
    {
        return $this->_urlBuilder->getUrl('customer/account/forgotpassword');
    }
}