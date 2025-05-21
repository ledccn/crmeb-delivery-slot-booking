<?php

namespace Ledc\DeliverySlotBooking\dao;

use app\dao\BaseDao;
use Ledc\DeliverySlotBooking\model\EbDeliveryTimeSlots;

/**
 * 预订配送时间段表
 */
class DeliveryTimeSlotsDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return EbDeliveryTimeSlots::class;
    }
}