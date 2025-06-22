<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

/**
 * 预订配送时间段管理
 */
class CreateDeliveryTimeSlots extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('delivery_time_slots', ['comment' => '预订配送时间段表', 'signed' => false]);
        $table->addColumn('title', AdapterInterface::PHINX_TYPE_STRING, ['limit' => MysqlAdapter::TEXT_TINY, 'comment' => '配送时间段名称', 'null' => false])
            ->addColumn('start_time', AdapterInterface::PHINX_TYPE_TIME, ['comment' => '开始时间', 'null' => false])
            ->addColumn('end_time', AdapterInterface::PHINX_TYPE_TIME, ['comment' => '结束时间', 'null' => false])
            ->addColumn(Column::tinyInteger('minutes_step')->setComment('分钟步长')->setNull(false)->setSigned(false)->setDefault(30))
            ->addColumn(Column::tinyInteger('enabled')->setComment('启用状态')->setNull(false)->setSigned(false)->setDefault(1))
            ->addColumn('create_time', 'datetime', ['comment' => '创建时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', 'datetime', ['comment' => '更新时间', 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['title'], ['unique' => true, 'name' => 'idx_title'])
            ->addIndex(['enabled'], ['name' => 'idx_enabled'])
            ->create();
    }
}
