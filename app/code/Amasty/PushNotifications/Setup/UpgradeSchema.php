<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Setup;

use Amasty\PushNotifications\Setup\Operation\CreateCampaignSegmentsTable;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Operation\CreateCampaignCustomerGroupTable
     */
    private $createCampaignCustomerGroupTable;

    /**
     * @var CreateCampaignSegmentsTable
     */
    private $createCampaignSegmentsTable;

    public function __construct(
        Operation\CreateCampaignCustomerGroupTable $createCampaignCustomerGroupTable,
        Operation\CreateCampaignSegmentsTable $createCampaignSegmentsTable
    ) {
        $this->createCampaignCustomerGroupTable = $createCampaignCustomerGroupTable;
        $this->createCampaignSegmentsTable = $createCampaignSegmentsTable;
    }

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$context->getVersion() || version_compare($context->getVersion(), '1.2.0', '<')) {
            $this->createCampaignCustomerGroupTable->execute($setup);
            $this->createCampaignSegmentsTable->execute($setup);
            $this->addSegmentationColumn($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addSegmentationColumn($setup)
    {
        $column = [
            'type' => Table::TYPE_BOOLEAN,
            'nullable' => false,
            'comment' => 'Segmentation Source Column',
            'default' => 0
        ];

        $setup->getConnection()->addColumn(
            $setup->getTable(Operation\CreateCampaignTable::TABLE_NAME),
            \Amasty\PushNotifications\Api\Data\CampaignInterface::SEGMENTATION_SOURCE,
            $column
        );
    }
}
