<?php

namespace Auraine\Brands\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        /*
         * Create table 'auraine_bestseller'
         */

        $table = $installer->getConnection()->newTable(
            $installer->getTable('auraine_shopbrand')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Brand Id'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Title'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Brand Logo'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Description'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'Status'
        )->addColumn(
            'is_popular',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
           null,
            [],
            'Is Popular'
        )->addColumn(
            'is_featured',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
           null,
            [],
            'Is featured'
        )->addColumn(
            'is_justin',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'Is Just launched'
        )->addColumn(
            'is_exclusive',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
             [],
            'Is Exclusive'
        )->setComment(
            'Auraine Gbookbrands'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
