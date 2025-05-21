<?php

namespace Ledc\DeliverySlotBooking\adminapi;

use app\adminapi\controller\AuthController;
use app\Request;
use Ledc\DeliverySlotBooking\model\EbDeliveryScheduleTemplates;
use Ledc\DeliverySlotBooking\services\DeliveryScheduleTemplatesService;
use Ledc\DeliverySlotBooking\validate\DeliveryScheduleTemplatesValidate;
use ReflectionException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\App;
use think\Response;

/**
 * 预订配送时间段模板
 */
class DeliveryScheduleTemplates extends AuthController
{
    /**
     * @var DeliveryScheduleTemplatesService
     */
    protected $services;

    /**
     * 构造函数
     * @param App $app
     * @param DeliveryScheduleTemplatesService $services
     */
    public function __construct(App $app, DeliveryScheduleTemplatesService $services)
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
        $where = $request->getMore(['day_of_week', 'enabled']);
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
            ['name', ''],
            'day_of_week',
            'time_slot_id',
            ['enabled', 1],
        ]);

        validate(DeliveryScheduleTemplatesValidate::class)->check($data);

        if ($id) {
            // 编辑
            $model = EbDeliveryScheduleTemplates::findOrFail($id);
            $model->save($data);
        } else {
            // 新增
            $model = EbDeliveryScheduleTemplates::create($data);
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
        EbDeliveryScheduleTemplates::destroy($ids);
        return response_json()->success('ok');
    }
}