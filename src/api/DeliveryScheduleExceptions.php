<?php

namespace Ledc\DeliverySlotBooking\api;

use app\Request;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleExceptions;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleExceptionsService;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Response;

/**
 * 预订配送时间段异常
 */
class DeliveryScheduleExceptions
{
    /**
     * @var DeliveryScheduleExceptionsService
     */
    protected DeliveryScheduleExceptionsService $services;

    /**
     * 构造函数
     * @param DeliveryScheduleExceptionsService $services
     */
    public function __construct(DeliveryScheduleExceptionsService $services)
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
        $where = [
            ['day', '>=', date('Y-m-d')],
            ['status',  '=', 0],
        ];
        return response_json()->success($this->services->getList($where));
    }

    /**
     * 显示指定的资源
     * @method GET
     * @param Request $request
     * @return Response
     */
    public function read(Request $request): Response
    {
        $id = $request->get('id/d');
        return response_json()->success('ok', EbDeliveryScheduleExceptions::with(['slots'])->findOrFail($id)->toArray());
    }
}