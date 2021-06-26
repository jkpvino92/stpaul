<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Form;

/**
 * Class Login
 * @package Tigren\Ajaxlogin\Block\Form
 */
class Login extends \Magento\Customer\Block\Form\Login
{

    /**
     * @var \Tigren\Ajaxlogin\Helper\Data
     */
    protected $_ajaxloginHelper;

    /**
     * Login constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Tigren\Ajaxlogin\Helper\Data $ajaxloginHelper
     * @param array $data
     */
    public function __construct
    (
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Tigren\Ajaxlogin\Helper\Data $ajaxloginHelper,
        array $data
    ) {
        parent::__construct($context, $customerSession, $customerUrl, $data);
        $this->_ajaxloginHelper = $ajaxloginHelper;
    }

    /**
     * @return mixed
     */
    public function isEnableSocialLogin()
    {
        return $this->_ajaxloginHelper->getScopeConfig('ajaxlogin/social_login/enable');
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->_ajaxloginHelper->isLoggedIn();
    }

}