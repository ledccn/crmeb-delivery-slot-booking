<?php

namespace Ledc\DeliverySlotBooking\dao;

use app\dao\BaseDao;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleTemplates;

/**
 * 预订配送时间段模板表
 */
class DeliveryScheduleTemplatesDao extends BaseDao
{
    /**
     * 设置模型
     * @return string
     */
    protected function setModel(): string
    {
        return EbDeliveryScheduleTemplates::class;
    }
}