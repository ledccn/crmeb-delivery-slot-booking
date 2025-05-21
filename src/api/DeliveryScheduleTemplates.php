<?php

namespace Ledc\DeliverySlotBooking\api;

use app\Request;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleTemplatesService;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Response;

/**
 * 预订配送时间段模板
 */
class DeliveryScheduleTemplates
{
    /**
     * @var DeliveryScheduleTemplatesService
     */
    protected DeliveryScheduleTemplatesService $services;

    /**
     * 构造函数
     * @param DeliveryScheduleTemplatesService $services
     */
    public function __construct(DeliveryScheduleTemplatesService $services)
    {
        $this->services = $services;
    }

    /**
     * 显示资源列表
     * @method GET
     * @param Request $request
     * @return Response
     * @throws ReflectionException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(Request $request): Response
    {
        $where = $request->getMore(['day_of_week']);
        $where['enabled'] = 1;
        return response_json()->success($this->services->getList(array_filter($where, function ($v) {
            return null !== $v && '' !== $v;
        })));
    }

    /**
     * 显示指定的资源
     * @method GET
     * @param Request $request
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function read(Request $request): Response
    {
        $id = $request->get('id/d');
        return response_json()->success('ok', $this->services->getDao()->get($id)->toArray());
    }
}