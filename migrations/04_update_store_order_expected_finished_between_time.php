<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;
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
     */
    public function change()
    {
        $table = $this->table("store_order");
        $table->addColumn('expected_finished_time', AdapterInterface::PHINX_TYPE_INTEGER, ['comment' => '预期送达时间', 'null' => false, 'default' => 0, 'signed' => false])
            ->addColumn('expected_finished_start_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '预期送达开始时间', 'null' => true])
            ->addColumn('expected_finished_end_time', AdapterInterface::PHINX_TYPE_DATETIME, ['comment' => '预期送达结束时间', 'null' => true])
            ->addIndex('expected_finished_time')
            ->update();
    }
}
