<?php

namespace Ledc\DeliverySlotBooking\services;

use app\services\BaseServices;
use Ledc\DeliverySlotBooking\dao\DeliveryScheduleTemplatesDao;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleTemplates;
use Ledc\DeliverySlotBooking\model\EbDeliveryTimeSlots;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;

/**
 * 预订配送时间段模板表
 */
class DeliveryScheduleTemplatesService extends BaseServices
{
    /**
     * @var DeliveryScheduleTemplatesDao
     */
    protected $dao;

    /**
     * @param DeliveryScheduleTemplatesDao $dao
     */
    public function __construct(DeliveryScheduleTemplatesDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return DeliveryScheduleTemplatesDao
     */
    public function getDao(): DeliveryScheduleTemplatesDao
    {
        return $this->dao;
    }

    /**
     * 获取列表
     * @param array $where
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException|ReflectionException
     */
    public function getList(array $where = []): array
    {
        $list = $this->dao->selectList($where, '*', 0, 0, DeliveryScheduleTemplatesDao::DEFAULT_ORDER, ['slots']);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 验证时间段是否在允许配送的时间段内
     * @param int $start_timestamp 开始时间戳
     * @param int $end_timestamp 结束时间戳
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function validate(int $start_timestamp, int $end_timestamp): bool
    {
        $date = date('Y-m-d', $start_timestamp);
        if ($date !== date('Y-m-d', $end_timestamp)) {
            throw new ValidateException('必须选择同一天');
        }
        if ($start_timestamp >= $end_timestamp) {
            throw new ValidateException('开始时间必须小于结束时间');
        }

        [$templates, $exceptionsTimeSlot, $exceptions] = DeliveryScheduleService::getDatabase($date);

        // 获取预订配送时间段 分钟步长的最小值
        $minutesStep = EbDeliveryTimeSlots::DEFAULT_MINUTES_STEP;
        /** @var EbDeliveryTimeSlots[]|array $slots */
        $slots = [];
        $templates->each(function (EbDeliveryScheduleTemplates $template) use (&$minutesStep, &$slots) {
            /** @var EbDeliveryTimeSlots $slot */
            $slot = EbDeliveryTimeSlots::findOrEmpty($template->time_slot_id);
            $minutesStep = min($minutesStep, $slot->minutes_step);
            $slots[] = $slot;
        });

        // 时间段必须为分钟步长
        if ($end_timestamp - $start_timestamp !== (int)($minutesStep * 60)) {
            throw new ValidateException('时间段必须为' . $minutesStep . '分钟');
        }

        foreach ([$start_timestamp, $end_timestamp] as $time) {
            if ($exceptionsTimeSlot && !$exceptionsTimeSlot->validateBetweenTime($time)) {
                return false;
            } else {
                $canBook = false;
                foreach ($slots as $slot) {
                    if (false === $canBook && $slot->validateBetweenTime($time)) {
                        $canBook = true;
                        break;
                    }
                }
                if (false === $canBook) {
                    return false;
                }
            }
        }

        return true;
    }
}
