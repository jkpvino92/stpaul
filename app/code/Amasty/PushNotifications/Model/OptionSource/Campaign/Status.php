<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\OptionSource\Campaign;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    const STATUS_PASSED = 0;
    const STATUS_SCHEDULED = 1;
    const STATUS_EDITED = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STATUS_PASSED, 'label'=> __('Passed')],
            ['value' => self::STATUS_SCHEDULED, 'label'=> __('Scheduled')],
            ['value' => self::STATUS_EDITED, 'label'=> __('Edited')]
        ];
    }
}
