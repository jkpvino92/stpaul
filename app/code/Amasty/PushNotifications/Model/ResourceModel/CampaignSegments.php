<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\ResourceModel;

use Amasty\PushNotifications\Api\Data\CampaignSegmentsInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CampaignSegments
 */
class CampaignSegments extends AbstractDb
{
    const TABLE_NAME = 'amasty_notifications_campaign_segments';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, CampaignSegmentsInterface::CAMPAIGN_SEGMENT_ID);
    }
}
