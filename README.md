# Crmeb单商户系统-预订配送时间段 & 商品配件 二合一插件

## 安装

`composer require ledc/crmeb-delivery-slot-booking`

## 使用说明

1. 安装完之后，请执行以下命令，安装插件的数据库迁移文件 `php think install:migrate:crmeb-delivery-slot-booking`

2. 执行数据库迁移 `php think migrate:run`

## 内置命令

1. `php think test:delivery:schedule` 测试配送时间段，打印结果数组

## 数据表

1. `delivery_time_slots` 配送时间段表
2. `delivery_schedule_templates` 配送时间段模板表
3. `delivery_schedule_exceptions` 配送时间段异常表

## 业务逻辑说明

1. 使用 `delivery_time_slots` 定义可以使用的配送时间段
2. 使用 `delivery_schedule_templates` 定义每周每天的配送时间段
3. 如果某一天在 `delivery_schedule_exceptions` 中有定义，则使用 `delivery_schedule_exceptions` 中定义的配送时间段，并通过
   `status` 控制当天的配送状态
4. 可以在 `delivery_schedule_templates` 自由组合多个时间段，并通过 `enabled` 控制启用状态，灵活切换配送时间段

## 助手类

- `Ledc\DeliverySlotBooking\Helper::get` 获取预订配送时间段
- `Ledc\DeliverySlotBooking\Helper::validate` 验证时间段是否在允许配送的时间段内

## 扩展建议

- 若需支持多仓库/区域不同配送时间，可在上述表中添加 `region_id` 或 `warehouse_id` 字段。
- 若需要限制每个时间段的最大订单数，可在 `delivery_time_slots` 表中添加 `max_orders` 字段并在业务层控制。

## 捐赠

![reward](reward.png)