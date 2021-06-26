<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Dailydeal extends Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_dailydeal';
        $this->_blockGroup = 'Um_Dailydeals';
        $this->_headerText = __('Dailydeals');
        $this->_addButtonLabel = __('Create New Dailydeal');
        parent::_construct();
    }
}
