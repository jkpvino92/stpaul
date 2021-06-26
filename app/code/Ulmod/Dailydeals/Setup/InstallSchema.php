<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table as DdlTable;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('um_dailydeals_dailydeal')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('um_dailydeals_dailydeal')
            )
            ->addColumn(
                'dailydeal_id',
                DdlTable::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Dailydeal ID'
            )
            ->addColumn(
                'um_product_sku',
                DdlTable::TYPE_TEXT,
                255,
                [],
                'Dailydeal Product Sku'
            )
            ->addColumn(
                'um_product_price',
                DdlTable::TYPE_DECIMAL,
                '12,4',
                [],
                'Dailydeal Product Price'
            )
            ->addColumn(
                'um_deal_enable',
                DdlTable::TYPE_INTEGER,
                1,
                [],
                'Dailydeal Enable Deal'
            )
            ->addColumn(
                'um_discount_type',
                DdlTable::TYPE_INTEGER,
                null,
                [],
                'Dailydeal Discount Type'
            )
            ->addColumn(
                'um_discount_amount',
                DdlTable::TYPE_DECIMAL,
                '12,4',
                [],
                'Dailydeal Discount Amount'
            )
            ->addColumn(
                'um_date_from',
                DdlTable::TYPE_DATETIME,
                null,
                [],
                'Dailydeal Date From'
            )
            ->addColumn(
                'um_date_to',
                DdlTable::TYPE_DATETIME,
                null,
                [],
                'Dailydeal Date To'
            )

            ->addColumn(
                'created_at',
                DdlTable::TYPE_TIMESTAMP,
                null,
                [],
                'Dailydeal Created At'
            )
            ->addColumn(
                'updated_at',
                DdlTable::TYPE_TIMESTAMP,
                null,
                [],
                'Dailydeal Updated At'
            )
            ->setComment('Dailydeal Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('um_dailydeals_dailydeal'),
                $setup->getIdxName(
                    $installer->getTable('um_dailydeals_dailydeal'),
                    ['um_product_sku'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['um_product_sku'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
