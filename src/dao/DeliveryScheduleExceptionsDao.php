<?php

namespace Ledc\DeliverySlotBooking\dao;

use app\dao\BaseDao;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleExceptions;

/**
 * 预订配送时间段异常表
 */
class DeliveryScheduleExceptionsDao extends BaseDao
{
    /**
     * 默认排序
     */
    public const DEFAULT_ORDER = 'day ASC';
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return EbDeliveryScheduleExceptions::class;
    }
}