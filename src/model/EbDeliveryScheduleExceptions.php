<?php

namespace Ledc\DeliverySlotBooking\model;

use think\db\BaseQuery;
use think\db\Query;
use think\Model;
use think\model\relation\HasOne;

/**
 * eb_delivery_schedule_exceptions 预订配送时间段异常表
 * @property integer $id (主键)
 * @property string $reason 备注原因
 * @property string $day 特殊日期
 * @property integer $time_slot_id 配送时间段ID
 * @property integer $status 配送状态 0:歇业 1:正常配送
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class EbDeliveryScheduleExceptions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eb_delivery_schedule_exceptions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';

    /**
     * 配送时间段
     * @return HasOne
     */
    public function slots(): HasOne
    {
        return $this->hasOne(EbDeliveryTimeSlots::class, 'id', 'time_slot_id');
    }

    /**
     * 根据日期查询
     * @param string $day
     * @return Query|BaseQuery
     */
    public static function queryByDay(string $day): Query
    {
        return (new static)->db()->where('day', $day);
    }

    /**
     * 配送状态
     * @return bool
     */
    public function isStatus(): bool
    {
        return (bool)$this->getAttr('status');
    }
}
