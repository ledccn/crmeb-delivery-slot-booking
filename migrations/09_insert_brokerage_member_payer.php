<?php

use Ledc\DeliverySlotBooking\Constants;
use think\migration\Migrator;

/**
 * 在系统配置表，插入付费会员分销权限
 */
class InsertBrokerageMemberPayer extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $row = $this->fetchRow("SELECT * FROM `eb_system_config` WHERE `menu_name` = 'brokerage_func_status'");
        if (!empty($row)) {
            $config_tab_id = $row['config_tab_id'];
            $this->table('system_config')->insert([
                'menu_name' => Constants::BROKERAGE_MEMBER_PAYER,
                'type' => 'switch',
                'input_type' => 'input',
                'config_tab_id' => $config_tab_id,
                'required' => '',
                'width' => 0,
                'high' => 0,
                'info' => '付费会员分销权限',
                'desc' => '用户成为付费会员时，开通分销权限',
                'status' => 1,
            ])->saveData();
        }
    }
}
