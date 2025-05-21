<?php

namespace Ledc\DeliverySlotBooking\validate;

use think\Validate;

/**
 * 预订配送时间段异常表验证
 */
class DeliveryScheduleExceptionsValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'reason' => 'require|max:255',
        'day' => 'require|date',
        'time_slot_id' => 'requireIf:status,1|number',
        'status' => 'require|number|in:0,1',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'reason.require' => '请输入备注原因',
        'reason.max' => '备注原因不能超过255个字符',
        'day.require' => '请输入特殊日期',
        'day.date' => '请输入正确的特殊日期格式',
        'time_slot_id.number' => '配送时间段ID必须为数字',
        'status.require' => '请设置配送状态',
        'status.number' => '配送状态必须为数字',
        'status.in' => '配送状态必须在0到1之间',
    ];
}