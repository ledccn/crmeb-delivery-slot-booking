<?php

use Phinx\Db\Adapter\AdapterInterface;
use think\migration\db\Column;
use think\migration\Migrator;

/**
 * 预订配送时间段模板表
 */
class CreateDeliveryScheduleTemplates extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('delivery_schedule_templates', ['comment' => '预订配送时间段模板表', 'signed' => false]);
        $table->addColumn(Column::string('name')->setComment('模板名称')->setNull(false)->setDefault(''))
            ->addColumn(Column::tinyInteger('day_of_week')->setComment('星期几')->setNull(false)->setSigned(false))
            ->addColumn(Column::unsignedInteger('time_slot_id')->setComment('配送时间段ID')->setNull(false))
            ->addColumn(Column::tinyInteger('enabled')->setComment('启用状态')->setNull(false)->setSigned(false)->setDefault(1))
            ->addColumn('create_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '创建时间', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '更新时间', 'null' => true, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('time_slot_id', 'delivery_time_slots', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['day_of_week', 'time_slot_id'], ['unique' => true, 'name' => 'idx_day_of_week_slot'])
            ->addIndex(['enabled'], ['name' => 'idx_enabled'])
            ->create();
    }
}
