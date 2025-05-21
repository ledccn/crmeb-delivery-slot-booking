<?php

namespace Ledc\DeliverySlotBooking\services;

use app\services\BaseServices;
use Ledc\DeliverySlotBooking\dao\DeliveryTimeSlotsDao;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 预订配送时间段表
 */
class DeliveryTimeSlotsService extends BaseServices
{
    /**
     * @var DeliveryTimeSlotsDao
     */
    protected $dao;

    /**
     * @param DeliveryTimeSlotsDao $dao
     */
    public function __construct(DeliveryTimeSlotsDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return DeliveryTimeSlotsDao
     */
    public function getDao(): DeliveryTimeSlotsDao
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
        $list = $this->dao->selectList($where);
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }
}