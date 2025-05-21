<?php

namespace Ledc\DeliverySlotBooking\model;

use think\Model;

/**
 * eb_delivery_time_slots 预订配送时间段表
 * @property integer $id (主键)
 * @property string $title 配送时间段名称
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 * @property integer $minutes_step 分钟步长
 * @property integer $enabled 启用状态
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class EbDeliveryTimeSlots extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eb_delivery_time_slots';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';

    /**
     * 默认的分钟步长
     */
    public const DEFAULT_MINUTES_STEP = 30;

    /**
     * 生成全天配送时间段数组
     * @param int $minutesStep 分钟步长（例如：15）
     * @return array 全天时间段数组
     */
    public static function generateTimeSlots(int $minutesStep): array
    {
        $timeSlots = [];
        $startTime = strtotime('00:00');
        $endTime = strtotime('23:59');

        for ($time = $startTime; $time <= $endTime; $time += $minutesStep * 60) {
            $timeSlots[] = date('H:i', $time);
        }

        return $timeSlots;
    }

    /**
     * 验证时间戳是否在当前时间段内
     * @param int $timestamp
     * @return bool
     */
    public function validateBetweenTime(int $timestamp): bool
    {
        $start_number = strtotime($this->start_time);
        $end_number = strtotime($this->end_time);
        if ($start_number <= $timestamp && $timestamp <= $end_number) {
            return true;
        }
        return false;
    }
}
