<?php

namespace Ledc\DeliverySlotBooking\adminapi;

use app\Request;
use Ledc\DeliverySlotBooking\services\ProductPartsServices;
use Ledc\DeliverySlotBooking\services\ProductPartsValueServices;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\Response;

/**
 * 商品配件
 */
class ProductPartsController
{
    /**
     * @var ProductPartsServices
     */
    protected ProductPartsServices $services;

    /**
     * 构造函数
     * @param ProductPartsServices $service
     */
    public function __construct(ProductPartsServices $service)
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
        $ids = $request->get('ids');
        if (empty($ids)) {
            $ids = [];
        } else {
            $ids = is_array($ids) ? $ids : explode(',', $ids);
        }

        return response_json()->success($this->services->getList($ids));
    }

    /**
     * @param int|string $id
     * @param Request $request
     * @return Response
     */
    public function save($id, Request $request): Response
    {
        $data = $request->post(false);
        $model = $this->services->save($id, $data);
        return response_json()->success([$model->getPk() => $model->parts_id]);
    }

    /**
     * 批量更新
     * @param Request $request
     * @return Response
     */
    public function batchUpdate(Request $request): Response
    {
        $data = $request->post(false);

        Db::transaction(function () use ($data) {
            $parts_id = $data['parts_id'] ?? 0;
            $parts = $data['parts'] ?? [];
            unset($data['parts']);
            $model = $this->services->save($parts_id, $data);
            $parts_id = $parts_id ?: $model->parts_id;

            $vServices = new ProductPartsValueServices();
            foreach ($parts as $item) {
                $id = $item['id'] ?? 0;
                $item['parts_id'] = $parts_id;
                $vServices->save($id, $item);
            }
        });

        return response_json()->success();
    }

    /**
     * 获取数据
     * @param int|string $id
     * @return Response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function read($id): Response
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
