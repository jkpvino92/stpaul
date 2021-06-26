<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Messages\Forgot;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class Success
 * @package Tigren\Ajaxlogin\Block\Messages\Forgot
 */
class Success extends Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Success constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(Template\Context $context, Registry $registry, array $data)
    {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
    }

    /**
     * @return mixed
     */
    public function getEmailFromLayout()
    {
        return $this->_coreRegistry->registry('email');
    }
}