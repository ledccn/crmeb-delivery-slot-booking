<?php

namespace Ledc\DeliverySlotBooking\validate;

use think\Validate;

/**
 * 预订配送时间段表验证
 */
class DeliveryTimeSlotsValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title' => 'require',
        'start_time' => 'require|dateFormat:H:i:s',
        'end_time' => 'require|dateFormat:H:i:s',
        'minutes_step' => 'require|in:10,15,20,30',
        'enabled' => 'require|boolean',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'title.require' => '请输入配送时间段名称',
        'start_time.require' => '请输入开始时间',
        'start_time.dateFormat' => '请输入正确的开始时间格式',
        'end_time.require' => '请输入结束时间',
        'end_time.dateFormat' => '请输入正确的结束时间格式',
        'minutes_step.require' => '请输入分钟步长间隔',
        'minutes_step.in' => '请输入正确的分钟步长间隔',
        'enabled.require' => '请设置启用状态',
        'enabled.boolean' => '请设置启用状态',
    ];
}