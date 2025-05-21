<?php
declare (strict_types=1);

namespace Ledc\DeliverySlotBooking;

use Ledc\DeliverySlotBooking\services\DeliveryScheduleService;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * 测试配送时间段
 */
class TestDeliveryScheduleCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('test:delivery:schedule')
            ->addArgument('date', Argument::OPTIONAL, '日期字符串（格式为 Y-m-d，例如：2025-05-01）')
            ->setDescription('测试配送时间段，打印结果数组');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        try {
            $date = $input->getArgument('date');
            $result = DeliveryScheduleService::get($date ?: date('Y-m-d'));
            var_dump($result);
        } catch (\Throwable $throwable) {
            $output->writeln($throwable->getMessage());
        }
    }
}
