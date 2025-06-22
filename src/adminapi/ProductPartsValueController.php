<?php

namespace Ledc\DeliverySlotBooking\adminapi;

use app\Request;
use Ledc\DeliverySlotBooking\services\ProductPartsValueServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Response;

/**
 * 商品配件值
 */
class ProductPartsValueController
{
    /**
     * @var ProductPartsValueServices
     */
    protected ProductPartsValueServices $services;

    /**
     * 构造函数
     * @param ProductPartsValueServices $service
     */
    public function __construct(ProductPartsValueServices $service)
    {
        $this->services = $service;
    }

    /**
     * 商品配件列表
     * @param Request $request
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(Request $request): Response
    {
        $where = $request->getMore(['parts_id']);
        $where = filter_where($where);
        return response_json()->success($this->services->getList($where));
    }

    /**
     * 保存
     * @param int|string $id
     * @param Request $request
     * @return Response
     */
    public function save($id, Request $request): Response
    {
        $data = $request->post(false);
        $this->services->save($id, $data);
        return response_json()->success();
    }

    /**
     * 获取数据
     * @param int|string $id
     * @param Request $request
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function read($id, Request $request): Response
    {
        $info = $this->services->read($id);
        return response_json()->success($info);
    }

    /**
     * 删除指定资源
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        $ids = $request->param('ids');
        if (empty($ids)) {
            return response_json()->fail('ids参数为空');
        }

        $this->services->del($ids);
        return response_json()->success();
    }
}
