<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\OptionSource\Campaign;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Module\Manager;

/**
 * Class SegmentationSource
 */
class SegmentationSource implements OptionSourceInterface
{
    const CUSTOMER_GROUPS = 0;
    const CUSTOMER_SEGMENTS = 1;

    /**
     * @var Manager
     */
    private $moduleManager;

    public function __construct(
        Manager $moduleManager
    ) {
        $this->moduleManager = $moduleManager;
    }

    public function toOptionArray()
    {
        $result = [
            [
                'value' => self::CUSTOMER_GROUPS,
                'label' => __('Use Customer Groups (Default)')
            ]
        ];

        if ($this->moduleManager->isOutputEnabled('Amasty_Segments')) {
            $result[] = [
                'value' => self::CUSTOMER_SEGMENTS,
                'label' => __('Use Amasty Customer Segments')
            ];
        }

        return $result;
    }
}
