<?php

namespace Ledc\DeliverySlotBooking\services;

use app\services\BaseServices;
use Ledc\DeliverySlotBooking\dao\DeliveryScheduleExceptionsDao;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 预订配送时间段异常表
 */
class DeliveryScheduleExceptionsService extends BaseServices
{
    /**
     * @var DeliveryScheduleExceptionsDao
     */
    protected $dao;

    /**
     * @param DeliveryScheduleExceptionsDao $dao
     */
    public function __construct(DeliveryScheduleExceptionsDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return DeliveryScheduleExceptionsDao
     */
    public function getDao(): DeliveryScheduleExceptionsDao
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
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, DeliveryScheduleExceptionsDao::DEFAULT_ORDER, ['slots']);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }
}