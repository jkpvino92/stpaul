<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Model;

class Dailydeal extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'um_dailydeals_dailydeal';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'um_dailydeals_dailydeal';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'um_dailydeals_dailydeal';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ulmod\Dailydeals\Model\ResourceModel\Dailydeal');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
