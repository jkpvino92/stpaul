<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxlogin\Block\Login;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class Twitter
 * @package Tigren\Ajaxlogin\Block\Login
 */
class Twitter extends Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Twitter constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(Template\Context $context, Registry $registry, array $data)
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getUrlLgoin()
    {
        return $this->_coreRegistry->registry('url');
    }

    /**
     *
     */
    public function createWindown()
    {

    }
}