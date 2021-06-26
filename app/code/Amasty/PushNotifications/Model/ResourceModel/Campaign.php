<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Model\ResourceModel;

use Amasty\PushNotifications\Api\Data\CampaignInterface;
use Amasty\PushNotifications\Setup\Operation\CreateCampaignStoreTable;
use Amasty\PushNotifications\Setup\Operation\CreateCampaignTable;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Campaign extends AbstractDb
{
    public function _construct()
    {
        $this->_init(CreateCampaignTable::TABLE_NAME, CampaignInterface::CAMPAIGN_ID);
    }

    /**
     * @param $id
     * @return array
     */
    public function getStoreIds($id)
    {
        $select = $this->getConnection()->select()->from(
            ['c' => $this->getTable(CreateCampaignStoreTable::TABLE_NAME)],
            ['store_id']
        )->where(
            'campaign_id = :campaign_id'
        );
        $bind = ['campaign_id' => (int)$id];

        return $this->getConnection()->fetchCol($select, $bind);
    }
}
