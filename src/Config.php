<?php

namespace Ledc\DeliverySlotBooking;

/**
 * 预约配送时间段配置类
 */
class Config
{
    /**
     * 配置前缀
     */
    public const CONFIG_PREFIX = 'delivery_schedule_';
    /**
     * 必须的配置项
     */
    public const REQUIRE_KEYS = [
        'preparation_time',
        'enabled',
    ];
    /**
     * 商品的准备时间（分钟）
     * - 指商品的准备时间、制作时间、出餐时间；说明：用户仅能提交该时间段之后的订单进行预约配送；默认为0，单位为分钟
     * @var int
     */
    protected int $preparation_time = 0;
    /**
     * 是否启用
     * @var bool
     */
    protected bool $enabled = false;

    /**
     * 构造函数
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * 是否启用
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * 商品的准备时间（分钟）
     * @return int
     */
    public function getPreparationTime(): int
    {
        return $this->preparation_time;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    /**
     * 转数组
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * 转字符串
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * 转字符串
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}