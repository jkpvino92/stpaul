<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Setup\Operation;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Amasty\PushNotifications\Api\Data\CampaignStoreInterface;
use Amasty\PushNotifications\Api\Data\CampaignInterface;

class CreateCampaignStoreTable
{
    const TABLE_NAME = 'amasty_notifications_campaign_store';

    /**
     * @param SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Exception
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->createTable(
            $this->createCampaignStoreTable($setup)
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     *
     * @throws \Zend_Db_Exception
     */
    public function createCampaignStoreTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::TABLE_NAME);

        return $setup->getConnection()
            ->newTable(
                $table
            )->addColumn(
                CampaignStoreInterface::ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Campaign store ID'
            )->addColumn(
                CampaignStoreInterface::CAMPAIGN_ID,
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false,],
                'Campaign Id'
            )->addColumn(
                CampaignStoreInterface::STORE_ID,
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )->addIndex(
                $setup->getIdxName(self::TABLE_NAME, ['campaign_id']),
                ['campaign_id']
            )->addForeignKey(
                $setup->getFkName(
                    self::TABLE_NAME,
                    CampaignStoreInterface::CAMPAIGN_ID,
                    CreateCampaignTable::TABLE_NAME,
                    CampaignInterface::CAMPAIGN_ID
                ),
                CampaignStoreInterface::CAMPAIGN_ID,
                $setup->getTable(CreateCampaignTable::TABLE_NAME),
                CampaignInterface::CAMPAIGN_ID,
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    self::TABLE_NAME,
                    CampaignStoreInterface::STORE_ID,
                    'store',
                    'store_id'
                ),
                CampaignStoreInterface::STORE_ID,
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Campaign Store'
            );
    }
}
