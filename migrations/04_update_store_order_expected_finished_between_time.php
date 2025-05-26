<?php

use Phinx\Db\Adapter\AdapterInterface;
use think\migration\Migrator;
use think\migration\db\Column;

/**
 * 更新订单表：预期送达时间段
 * - expected_finished_start_time、expected_finished_end_time字段
 */
class UpdateStoreOrderExpectedFinishedBetweenTime extends Migrator
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
        $table = $this->table("store_order");
        $table->addColumn('expected_finished_start_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '预期送达开始时间', 'null' => true])
            ->addColumn('expected_finished_end_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '预期送达结束时间', 'null' => true])
            ->addIndex('expected_finished_start_time')
            ->update();
    }
}
