<?php

namespace Ledc\DeliverySlotBooking\model;

use think\Model;

/**
 * 商品配件值
 * @property int $id 主键
 * @property int $parts_id 外键：配件
 * @property string $parts_name 名称
 * @property float $parts_price 加价
 * @property string $parts_image 图片
 * @property boolean|int $checked 是否选中
 * @property int $sort 排序
 * @property string $verify_hash 校验哈希
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 */
class ProductPartsValue extends Model
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_product_parts_value';

    /**
     * 数据表主键 复合主键使用数组定义
     * @var string|array
     */
    protected $pk = 'id';

    /**
     * 【模型事件】查询后
     * @param self $model
     * @return void
     */
    public static function onAfterRead(self $model): void
    {
        // TODO: Change the autogenerated stub
    }

    /**
     * 【模型事件】新增前
     * @param self $model
     * @return bool|null
     */
    public static function onBeforeInsert(self $model): ?bool
    {
        // TODO: Change the autogenerated stub
        return true;
    }

    /**
     * 【模型事件】新增后
     * @param self $model
     * @return void
     */
    public static function onAfterInsert(self $model): void
    {
        // TODO: Change the autogenerated stub
    }

    /**
     * 【模型事件】更新前
     * @param self $model
     * @return bool|null
     */
    public static function onBeforeUpdate(self $model): ?bool
    {
        // TODO: Change the autogenerated stub
        return true;
    }

    /**
     * 【模型事件】更新后
     * @param self $model
     * @return void
     */
    public static function onAfterUpdate(self $model): void
    {
        // TODO: Change the autogenerated stub
    }

    /**
     * 【模型事件】删除前
     * @param self $model
     * @return bool|null
     */
    public static function onBeforeDelete(self $model): ?bool
    {
        // TODO: Change the autogenerated stub
        return true;
    }

    /**
     * 【模型事件】删除后
     * @param self $model
     * @return void
     */
    public static function onAfterDelete(self $model): void
    {
        // TODO: Change the autogenerated stub
    }

    /**
     * 【模型事件】写入前
     * @param self $model
     * @return bool|null
     */
    public static function onBeforeWrite(self $model): ?bool
    {
        $model->verify_hash = md5(implode('|', [$model->parts_id, $model->parts_name, $model->parts_price, $model->parts_image]));
        return true;
    }

    /**
     * 【模型事件】写入后
     * @param self $model
     * @return void
     */
    public static function onAfterWrite(self $model): void
    {
    }

    /**
     * 【模型事件】恢复前
     * @param self $model
     * @return void
     */
    public static function onBeforeRestore(self $model): void
    {
        // TODO: Change the autogenerated stub
    }

    /**
     * 【模型事件】恢复后
     * @param self $model
     * @return void
     */
    public static function onAfterRestore(self $model): void
    {
        // TODO: Change the autogenerated stub
    }
}
