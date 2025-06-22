<?php

namespace Ledc\DeliverySlotBooking\services;

use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use Ledc\DeliverySlotBooking\model\ProductPartsValue;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 商品配件值
 */
class ProductPartsValueServices extends BaseServices
{
    /**
     * 获取商品配件值列表
     * @param array $where
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList(array $where = []): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = ProductPartsValue::where($where)->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order(['sort' => 'desc', 'id' => 'desc'])->select();

        $count = $list->count();
        return compact('list', 'count');
    }

    /**
     * 保存
     * @param int|null $id
     * @param array $data
     * @return void
     */
    public function save(?int $id, array $data): void
    {
        if ($id) {
            unset($data['id']);
            $res = ProductPartsValue::update($data, ['id' => $id]);
        } else {
            $res = ProductPartsValue::create($data);
        }

        if (!$res) {
            throw new AdminException(100006);
        }
    }

    /**
     * 获取一条数据
     * @param int $id
     * @return ProductPartsValue|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function read(int $id): ?ProductPartsValue
    {
        /** @var ProductPartsValue|null $model */
        $model = ProductPartsValue::find($id);
        return $model;
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function del(array $ids): bool
    {
        return ProductPartsValue::destroy($ids);
    }
}
