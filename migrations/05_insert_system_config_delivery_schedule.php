<?php

use think\migration\Migrator;
use think\migration\db\Column;

/**
 * 预约配送设置
 */
class InsertSystemConfigDeliverySchedule extends Migrator
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
        $systemConfigTab = $this->fetchRow("SELECT * FROM `eb_system_config_tab` WHERE `eng_title` = 'order_config'");
        if (empty($systemConfigTab)) {
            throw new InvalidArgumentException('未找到订单配置');
        }
        // 插入配置分类表
        $this->table('system_config_tab')->insert([
            'pid' => $systemConfigTab['id'],
            'title' => '预约配送设置',
            'eng_title' => 'delivery_schedule',
            'status' => 1,
            'info' => 0,
            'icon' => 's-promotion',
            'type' => 0,
            'sort' => 0,
            'menus_id' => $systemConfigTab['menus_id'],
        ])->saveData();

        $configTab = $this->fetchRow("SELECT * FROM `eb_system_config_tab` WHERE `eng_title` = 'delivery_schedule'");
        $config_tab_id = $configTab['id'];

        $systemConfigList = [
            [
                'menu_name' => \Ledc\DeliverySlotBooking\Config::CONFIG_PREFIX . 'preparation_time',
                'type' => 'text',
                'input_type' => 'input',
                'config_tab_id' => $config_tab_id,
                'required' => 'required:true',
                'width' => 100,
                'high' => 0,
                'value' => '"0"',
                'info' => '商品的准备时间',
                'desc' => '指商品的准备时间、制作时间、出餐时间；用户仅能提交该时间段之后的订单；默认为0，单位为分钟',
                'status' => 1,
            ],
            [
                'menu_name' => \Ledc\DeliverySlotBooking\Config::CONFIG_PREFIX . 'enabled',
                'type' => 'switch',
                'input_type' => 'input',
                'config_tab_id' => $config_tab_id,
                'required' => '',
                'width' => 0,
                'high' => 0,
                'info' => '启用',
                'desc' => '预约配送时间段功能的全局开关',
                'status' => 1,
            ],
        ];
        $this->table('system_config')
            ->insert($systemConfigList)
            ->saveData();
    }
}
