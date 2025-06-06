<?php

namespace Ledc\DeliverySlotBooking;

use crmeb\services\SystemConfigService;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleService;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleTemplatesService;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 助手类
 */
class Helper
{
    /**
     * 预约配送时间段功能是否启用
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return sys_config(Config::CONFIG_PREFIX . 'enabled', false);
    }

    /**
     * 获取预约配送时间默认时间戳
     * @return int
     */
    public static function appointmentTimestamp(): int
    {
        return time() + Helper::config()->getPreparationTime() * 60;
    }

    /**
     * 获取配置
     * @return array
     */
    public static function getConfig(): array
    {
        // 从数据库取配置
        $result = SystemConfigService::more(array_map(fn($key) => Config::CONFIG_PREFIX . $key, Config::REQUIRE_KEYS), false);
        // 移除配置前缀
        $keys = array_map(fn($key) => substr($key, strlen(Config::CONFIG_PREFIX)), array_keys($result));
        return array_combine($keys, array_values($result));
    }

    /**
     * 获取预约时间段配置对象
     * @return Config
     */
    public static function config(): Config
    {
        /** @var App $app */
        $app = app();
        if ($app->exists(Config::class)) {
            return $app->make(Config::class);
        }

        $systemConfig = self::getConfig();
        // 实例化
        $config = new Config($systemConfig);
        // 绑定类实例到容器
        $app->instance(Config::class, $config);

        return $config;
    }

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