<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block;

use Magento\Framework\View\Element\Html\Link as HtmlLink;
use Magento\Framework\View\Element\Template\Context;
use Ulmod\Dailydeals\Model\Config as ConfigModel;

class TopLink extends HtmlLink
{
     /**
      * @var ConfigModel
      */
    protected $configModel;

    /**
     * @param Context    $context
     * @param ConfigModel $configModel
     */
    public function __construct(
        Context $context,
        ConfigModel $configModel,
        array $data = []
    ) {
        $this->configModel = $configModel;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getLabel()
    {

       $topLinkStatus = $this->configModel->isShowItemTop();
        if (!$topLinkStatus) {
            return '';
        }

       return $this->configModel->getTopLinkText();
    } 

    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        return '<li><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }
}