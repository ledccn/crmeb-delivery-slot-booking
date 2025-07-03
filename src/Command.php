<?php
declare (strict_types=1);

namespace Ledc\DeliverySlotBooking;

use Ledc\ThinkModelTrait\Contracts\HasMigrationCommand;
use think\console\Input;
use think\console\Output;

/**
 * 安装数据库迁移文件
 */
class Command extends \think\console\Command
{
    use HasMigrationCommand;

    /**
     * @return void
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('install:migrate:crmeb-delivery-slot-booking')
            ->setDescription('安装插件的数据库迁移文件');

        // 迁移文件映射
        $this->setFileMaps([
            'CreateDeliveryTimeSlots' => dirname(__DIR__) . '/migrations/01_create_delivery_time_slots.php',
            'CreateDeliveryScheduleTemplates' => dirname(__DIR__) . '/migrations/02_create_delivery_schedule_templates.php',
            'CreateDeliveryScheduleExceptions' => dirname(__DIR__) . '/migrations/03_create_delivery_schedule_exceptions.php',
            'UpdateStoreOrderExpectedFinishedBetweenTime' => dirname(__DIR__) . '/migrations/04_update_store_order_expected_finished_between_time.php',
            'InsertSystemConfigDeliverySchedule' => dirname(__DIR__) . '/migrations/05_insert_system_config_delivery_schedule.php',
            'CreateStoreProductParts' => dirname(__DIR__) . '/migrations/06_create_store_product_parts.php',
            'CreateStoreProductPartsValue' => dirname(__DIR__) . '/migrations/07_create_store_product_parts_value.php',
            'UpdateProductOrCartParts' => dirname(__DIR__) . '/migrations/08_update_product_or_cart_parts.php',
            'InsertBrokerageMemberPayer' => dirname(__DIR__) . '/migrations/09_insert_brokerage_member_payer.php',
        ]);
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $this->eachFileMaps($input, $output);
    }
}
