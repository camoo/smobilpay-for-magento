<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Camoo
 * @package     Camoo_Enkap
 */

namespace Camoo\Enkap\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * Install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup, 
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if ($installer->tableExists('quote')) {
            $tableName = $setup->getTable('quote');
            $connection = $setup->getConnection();
            if (!$connection->tableColumnExists($tableName, 'order_transaction_id')) {
                $connection->addColumn(
                    $tableName,
                    'order_transaction_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by Izipresta for the Enkap Payment',
                        'length' => 255
                    ]
                );
            }
            if (!$connection->tableColumnExists($tableName, 'merchant_reference')) {
                $connection->addColumn(
                    $tableName,
                    'merchant_reference',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by Izipresta for the Enkap Payment',
                        'length' => 255
                    ]
                );
            }
        }

        if ($installer->tableExists('sales_order')) {
            $tableName = $setup->getTable('sales_order');
            $connection = $setup->getConnection();
            if (!$connection->tableColumnExists($tableName, 'order_transaction_id')) {
                $connection->addColumn(
                    $tableName,
                    'order_transaction_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by Izipresta for the Enkap Payment',
                        'length' => 255
                    ]
                );
            }
            if (!$connection->tableColumnExists($tableName, 'merchant_reference')) {
                $connection->addColumn(
                    $tableName,
                    'merchant_reference',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by Izipresta for the Enkap Payment',
                        'length' => 255
                    ]
                );
            }
        }
        $installer->endSetup();
    }
}
