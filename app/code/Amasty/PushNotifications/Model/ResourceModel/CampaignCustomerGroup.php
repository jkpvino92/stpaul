<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\ResourceModel;

use Amasty\PushNotifications\Api\Data\CampaignCustomerGroupInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CampaignCustomerGroup
 */
class CampaignCustomerGroup extends AbstractDb
{
    const TABLE_NAME = 'amasty_notifications_campaign_group';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, CampaignCustomerGroupInterface::CAMPAIGN_GROUP_ID);
    }
}
