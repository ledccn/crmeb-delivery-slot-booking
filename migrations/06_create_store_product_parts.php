<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

/**
 * 创建商品配件表
 */
class CreateStoreProductParts extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('store_product_parts', ['id' => false, 'engine' => 'InnoDB', 'comment' => '商品配件']);
        $table->addColumn('parts_id', AdapterInterface::PHINX_TYPE_INTEGER, ['signed' => false, 'identity' => true, 'comment' => '主键'])->setPrimaryKey('parts_id')
            ->addColumn('title', AdapterInterface::PHINX_TYPE_STRING, ['limit' => 50, 'null' => false, 'comment' => '配件名称'])
            ->addColumn('multiple', AdapterInterface::PHINX_TYPE_INTEGER, ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 1, 'comment' => '多选'])
            ->addColumn('multiple_min', AdapterInterface::PHINX_TYPE_INTEGER, ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 1, 'comment' => '最少选择数'])
            ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
