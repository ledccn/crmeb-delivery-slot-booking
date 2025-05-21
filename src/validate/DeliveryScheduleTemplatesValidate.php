<?php

namespace Ledc\DeliverySlotBooking\validate;

use think\Validate;

/**
 * 预订配送时间段模板表验证
 */
class DeliveryScheduleTemplatesValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require|max:255',
        'day_of_week' => 'require|number|between:0,6',
        'time_slot_id' => 'require|number',
        'enabled' => 'require|boolean',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '请输入模板名称',
        'name.max' => '模板名称不能超过255个字符',
        'day_of_week.require' => '请输入星期几',
        'day_of_week.number' => '星期几必须为数字',
        'day_of_week.between' => '星期几必须在0到6之间',
        'time_slot_id.require' => '请输入配送时间段ID',
        'time_slot_id.number' => '配送时间段ID必须为数字',
        'enabled.require' => '请设置启用状态',
        'enabled.boolean' => '请设置启用状态',
    ];
}