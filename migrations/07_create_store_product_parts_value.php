<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

/**
 * 创建商品配件值表
 */
class CreateStoreProductPartsValue extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('store_product_parts_value', ['engine' => 'InnoDB', 'comment' => '商品配件值', 'signed' => false]);
        $table->addColumn('parts_id', AdapterInterface::PHINX_TYPE_INTEGER, ['comment' => '外键：配件', 'null' => false, 'signed' => false])
            ->addColumn('parts_name', AdapterInterface::PHINX_TYPE_STRING, ['limit' => 100, 'comment' => '配件名称', 'null' => false])
            ->addColumn('parts_price', AdapterInterface::PHINX_TYPE_DECIMAL, ['precision' => 8, 'scale' => 2, 'comment' => '配件价格', 'null' => false, 'signed' => false, 'default' => 0.00])
            ->addColumn('parts_image', AdapterInterface::PHINX_TYPE_STRING, ['limit' => 300, 'comment' => '配件图片', 'null' => false, 'default' => ''])
            ->addColumn('checked', AdapterInterface::PHINX_TYPE_BOOLEAN, ['comment' => '是否选中', 'null' => false, 'default' => false])
            ->addColumn('sort', AdapterInterface::PHINX_TYPE_SMALL_INTEGER, ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '排序', 'null' => false, 'signed' => false, 'default' => 100])
            ->addColumn('verify_hash', AdapterInterface::PHINX_TYPE_CHAR, ['limit' => 32, 'comment' => '验证哈希', 'null' => false])
            ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('parts_id', 'store_product_parts', 'parts_id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['parts_id', 'parts_name'], ['name' => 'idx_unique_parts', 'unique' => true])
            ->addIndex(['parts_id'], ['name' => 'idx_parts_id'])
            ->create();
    }
}
