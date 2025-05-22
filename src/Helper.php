<?php

namespace Ledc\DeliverySlotBooking;

use Ledc\DeliverySlotBooking\services\DeliveryScheduleService;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleTemplatesService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 助手类
 */
class Helper
{
    /**
     * 获取预订配送时间段
     * @param string $date 日期字符串（格式为 Y-m-d，例如：2025-05-01）
     * @param bool $filter
     * @return array
     */
    public static function get(string $date, bool $filter = true): array
    {
        return DeliveryScheduleService::get($date, $filter);
    }

    /**
     * 验证时间段是否在允许配送的时间段内
     * @param int $start_timestamp 开始时间戳
     * @param int $end_timestamp 结束时间戳
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function validate(int $start_timestamp, int $end_timestamp): bool
    {
        return DeliveryScheduleTemplatesService::validate($start_timestamp, $end_timestamp);
    }
}