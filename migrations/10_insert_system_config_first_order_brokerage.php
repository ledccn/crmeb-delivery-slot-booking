<?php

use Ledc\DeliverySlotBooking\ShareProfitHelper;
use think\migration\Migrator;

/**
 * 添加首笔订单奖励（直推奖、间推奖）
 */
class InsertSystemConfigFirstOrderBrokerage extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $row = $this->fetchRow("SELECT * FROM `eb_system_config` WHERE `menu_name` = 'brokerage_user_status'");
        if (!empty($row)) {
            $config_tab_id = $row['config_tab_id'];
            $this->table('system_config')->insert([
                [
                    'menu_name' => ShareProfitHelper::FIRST_ORDER_ONE_BROKERAGE_AMOUNT,
                    'type' => 'text',
                    'input_type' => 'number',
                    'config_tab_id' => $config_tab_id,
                    'required' => '',
                    'width' => 100,
                    'high' => 0,
                    'value' => 0,
                    'info' => '首单直推奖',
                    'desc' => '每位用户首次成单时给直推人发放的拉新佣金，终生仅发放一次。',
                    'status' => 1,
                ],
                [
                    'menu_name' => ShareProfitHelper::FIRST_ORDER_TWO_BROKERAGE_AMOUNT,
                    'type' => 'text',
                    'input_type' => 'number',
                    'config_tab_id' => $config_tab_id,
                    'required' => '',
                    'width' => 100,
                    'high' => 0,
                    'value' => 0,
                    'info' => '首单间推奖',
                    'desc' => '每位用户首次成单时给间推人发放的拉新佣金，终生仅发放一次。',
                    'status' => 1,
                ],
            ])->saveData();
        }
    }
}
