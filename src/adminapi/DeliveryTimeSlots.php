<?php

namespace Ledc\DeliverySlotBooking\adminapi;

use app\adminapi\controller\AuthController;
use app\Request;
use Ledc\DeliverySlotBooking\model\EbDeliveryTimeSlots;
use Ledc\DeliverySlotBooking\services\DeliveryTimeSlotsService;
use Ledc\DeliverySlotBooking\validate\DeliveryTimeSlotsValidate;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\App;
use think\Response;

/**
 * 预订配送时间段
 */
class DeliveryTimeSlots extends AuthController
{
    /**
     * @var DeliveryTimeSlotsService
     */
    protected $services;

    /**
     * 构造函数
     * @param App $app
     * @param DeliveryTimeSlotsService $services
     */
    public function __construct(App $app, DeliveryTimeSlotsService $services)
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
        $where = $request->getMore(['enabled']);
        return response_json()->success($this->services->getList(array_filter($where, function ($v) {
            return null !== $v && '' !== $v;
        })));
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
            'title',
            'start_time',
            'end_time',
            ['minutes_step', EbDeliveryTimeSlots::DEFAULT_MINUTES_STEP],
            ['enabled', 1],
        ]);

        validate(DeliveryTimeSlotsValidate::class)->check($data);

        if ($id) {
            // 编辑
            $model = EbDeliveryTimeSlots::findOrFail($id);
            $model->save($data);
        } else {
            // 新增
            $model = EbDeliveryTimeSlots::create($data);
        }

        return response_json()->success('ok', $model->toArray());
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

    /**
     * 删除指定资源
     * @method DELETE
     * @param int|string $id
     * @return Response
     */
    public function delete($id): Response
    {
        $ids = is_array($id) ? $id : explode('_', $id);
        EbDeliveryTimeSlots::destroy($ids);
        return response_json()->success('ok');
    }
}