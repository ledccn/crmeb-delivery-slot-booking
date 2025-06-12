<?php

namespace Ledc\DeliverySlotBooking\services;

use Ledc\DeliverySlotBooking\Helper;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleExceptions;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleTemplates;
use Ledc\DeliverySlotBooking\model\EbDeliveryTimeSlots;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;
use Throwable;

/**
 * 预订配送时间段
 * - 结合预订配送时间段模板和预订配送时间段异常
 */
class DeliveryScheduleService
{
    /**
     * 配送时间段的结束时间偏移量
     */
    private const OFFSET_SECONDS = 60;
    /**
     * 获取预订配送时间段
     * @param string $date 日期字符串（格式为 Y-m-d，例如：2025-05-01）
     * @param bool $filter
     * @return array
     */
    public static function get(string $date, bool $filter = true): array
    {
        try {
            $day = date('Y-m-d', strtotime($date));
            $today = date('Y-m-d');

            /**
             * @var Collection|EbDeliveryScheduleTemplates[] $templates
             * @var EbDeliveryTimeSlots|null $exceptionsTimeSlot
             * @var EbDeliveryScheduleExceptions|null $exceptions
             */
            [$templates, $exceptionsTimeSlot, $exceptions] = self::getDatabase($day);

            // 获取预订配送时间段 分钟步长的最小值
            $minutesStep = EbDeliveryTimeSlots::MAX_MINUTES_STEP;
            /** @var EbDeliveryTimeSlots[]|array $slots */
            $slots = [];
            $templates->each(function (EbDeliveryScheduleTemplates $template) use (&$minutesStep, &$slots) {
                /** @var EbDeliveryTimeSlots $slot */
                $slot = EbDeliveryTimeSlots::findOrEmpty($template->time_slot_id);
                $minutesStep = min($minutesStep, $slot->minutes_step);
                $slots[] = $slot;
            });

            // 按分钟步长生成全天的 配送数组
            $dateTimeSlots = [];
            $startTime = strtotime('00:00');
            $endTime = strtotime('23:59');
            // 商品的准备时间
            $preparation_min_time = Helper::appointmentTimestamp();
            $flag = true;
            for ($time = $startTime; $time <= $endTime; $time += $minutesStep * 60) {
                if ($filter && $day === $today && $time < $preparation_min_time) {
                    continue;
                }

                if ($exceptionsTimeSlot) {
                    // 当天的时间段异常，合并到 配送数组中【高优先级】
                    if ($exceptionsTimeSlot->validateBetweenTime($time)) {
                        $dateTimeSlots[$time] = date('H:i', $time);
                    }
                } else {
                    // 当天的时间段模板，合并到 配送数组中
                    array_map(function (EbDeliveryTimeSlots $timeSlot) use (&$dateTimeSlots, $time) {
                        if ($timeSlot->validateBetweenTime($time)) {
                            $dateTimeSlots[$time] = date('H:i', $time);
                        }
                    }, $slots);
                }

                if ($flag && $today !== date('Y-m-d', $time + $minutesStep * 60)) {
                    $flag = false;
                    $time = $time - self::OFFSET_SECONDS;
                }
            }

            // 根据键名升序排序
            if (empty($dateTimeSlots)) {
                throw new ValidateException('可用的配送时间段为空');
            } else {
                ksort($dateTimeSlots, SORT_NUMERIC);
                $dateTimeSlots = array_values($dateTimeSlots);
                if (count($dateTimeSlots) < 2) {
                    throw new ValidateException('剩余的配送时间段不足');
                }
            }

            // 确保双数的配送数组
            $result = [];
            $seconds = (int)($minutesStep * 60);
            while (1 < count($dateTimeSlots)) {
                $start = array_shift($dateTimeSlots);
                $end = array_shift($dateTimeSlots);
                $diff_time = strtotime($end) - strtotime($start);
                if ($diff_time === $seconds || $diff_time + self::OFFSET_SECONDS === $seconds) {
                    $result[] = [$start, $end];
                }
                array_unshift($dateTimeSlots, $end);
            }

            return $result;
        } catch (Throwable $throwable) {
            throw new ValidateException($throwable->getMessage());
        }
    }

    /**
     * 获取预订配送时间段
     * @param string $date 日期字符串（格式为 Y-m-d，例如：2025-05-01）
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getDatabase(string $date): array
    {
        $week = date('w', strtotime($date)); // 星期几
        // 获取预订配送时间段模板
        $templates = EbDeliveryScheduleTemplates::queryByDayOfWeek($week)->select();
        if ($templates->isEmpty()) {
            throw new ValidateException('商家未设置配送时间段');
        }
        // 获取预订配送时间段异常
        /** @var EbDeliveryScheduleExceptions|null $exceptions */
        $exceptions = EbDeliveryScheduleExceptions::queryByDay($date)->find();
        /** @var EbDeliveryTimeSlots|null $exceptionsTimeSlot */
        $exceptionsTimeSlot = null;
        if ($exceptions) {
            if (!$exceptions->isStatus()) {
                throw new ValidateException('该日期暂不配送：' . $exceptions->reason);
            }
            if ($exceptions->time_slot_id) {
                $exceptionsTimeSlot = EbDeliveryTimeSlots::findOrEmpty($exceptions->time_slot_id);
            }
        }

        return [$templates, $exceptionsTimeSlot, $exceptions];
    }
}
