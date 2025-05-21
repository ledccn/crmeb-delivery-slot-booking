<?php

namespace Ledc\DeliverySlotBooking\adminapi;

use app\adminapi\controller\AuthController;
use app\Request;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleExceptions;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleExceptionsService;
use Ledc\DeliverySlotBooking\validate\DeliveryScheduleExceptionsValidate;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\App;
use think\Response;

/**
 * 预订配送时间段异常
 */
class DeliveryScheduleExceptions extends AuthController
{
    /**
     * @var DeliveryScheduleExceptionsService
     */
    protected $services;

    /**
     * 构造函数
     * @param App $app
     * @param DeliveryScheduleExceptionsService $services
     */
    public function __construct(App $app, DeliveryScheduleExceptionsService $services)
    {
        parent::__construct($app);
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
        $where = [];
        // 等值查询
        $eqWhere = $request->getMore(['time_slot_id', 'status']);
        $eqWhere = array_filter($eqWhere, function ($v) {
            return null !== $v && '' !== $v;
        });

        // 比较查询
        $day = $request->get('day');
        if ($day) {
            $where = ['day', '>=', $day];
        }
        if ($eqWhere) {
            foreach ($eqWhere as $key => $value) {
                $where[] = [$key, '=', $value];
            }
        }
        return response_json()->success($this->services->getList($where));
    }

    /**
     * 保存新建的资源
     * @method POST
     * @param Request $request
     * @return Response
     */
    public function save(Request $request): Response
    {
        $id = $request->post('id/d', 0);
        $data = $request->postMore([
            'reason',
            'day',
            'time_slot_id',
            ['status', 1],
        ]);

        validate(DeliveryScheduleExceptionsValidate::class)->check($data);

        if ($id) {
            // 编辑
            $model = EbDeliveryScheduleExceptions::findOrFail($id);
            $model->save($data);
        } else {
            // 新增
            $model = EbDeliveryScheduleExceptions::create($data);
        }

        return response_json()->success('ok', $model->toArray());
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

    /**
     * 删除指定资源
     * @method DELETE
     * @param int|string $id
     * @return Response
     */
    public function delete($id): Response
    {
        $ids = is_array($id) ? $id : explode('_', $id);
        EbDeliveryScheduleExceptions::destroy($ids);
        return response_json()->success('ok');
    }
}