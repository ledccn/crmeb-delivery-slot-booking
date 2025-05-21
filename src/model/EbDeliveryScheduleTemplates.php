<?php

namespace Ledc\DeliverySlotBooking\model;

use think\db\BaseQuery;
use think\db\Query;
use think\Model;
use think\model\relation\HasOne;

/**
 * eb_delivery_schedule_templates 预订配送时间段模板表
 * @property integer $id (主键)
 * @property string $name 模板名称
 * @property integer $day_of_week 星期几
 * @property integer $time_slot_id 配送时间段ID
 * @property integer $enabled 启用状态
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class EbDeliveryScheduleTemplates extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eb_delivery_schedule_templates';

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
     * 根据星期几查询
     * @param int $dayOfWeek 星期几（星期日-星期六 0-6）
     * @param bool $enabled 是否启用
     * @return Query|BaseQuery
     */
    public static function queryByDayOfWeek(int $dayOfWeek, bool $enabled = true): Query
    {
        return (new static)->db()->where('day_of_week', $dayOfWeek)->where('enabled', $enabled ? 1 : 0);
    }
}
