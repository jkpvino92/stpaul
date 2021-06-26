<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Messages\Login;

use Magento\Framework\View\Element\Template;

/**
 * Class Success
 * @package Tigren\Ajaxlogin\Block\Messages\Login
 */
class Success extends Template
{
    /**
     * Success constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
    }
}