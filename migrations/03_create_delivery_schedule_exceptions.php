<?php

use Phinx\Db\Adapter\AdapterInterface;
use think\migration\Migrator;
use think\migration\db\Column;

/**
 * 预订配送时间段异常表
 */
class CreateDeliveryScheduleExceptions extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('delivery_schedule_exceptions', ['comment' => '预订配送时间段异常表', 'signed' => false]);
        $table->addColumn(Column::string('reason')->setComment('备注原因')->setNull(false))
            ->addColumn(Column::date('day')->setComment('特殊日期')->setNull(false))
            ->addColumn(Column::unsignedInteger('time_slot_id')->setComment('配送时间段ID')->setNull(true)->setDefault(null))
            ->addColumn(Column::tinyInteger('status')->setComment('配送状态 0:歇业 1:正常配送')->setNull(false)->setSigned(false)->setDefault(0))
            ->addColumn('create_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '创建时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '更新时间', 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('time_slot_id', 'delivery_time_slots', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['day'], ['unique' => true, 'name' => 'idx_day'])
            ->addIndex(['status'], ['name' => 'idx_status'])
            ->create();
    }
}
