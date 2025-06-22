<?php

namespace Ledc\DeliverySlotBooking\services;

use app\model\product\product\StoreProduct;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use Ledc\DeliverySlotBooking\model\ProductParts;
use RuntimeException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Log;
use Throwable;

/**
 * 商品配件
 */
class ProductPartsServices extends BaseServices
{
    /**
     * 获取商品配件列表
     * @param array $ids
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList(array $ids = []): array
    {
        [$page, $limit] = $this->getPageValue();
        if (empty($ids)) {
            $query = ProductParts::with(ProductParts::withParts());
        } else {
            $query = ProductParts::with(ProductParts::withParts())->whereIn('parts_id', $ids);
        }
        $list = $query->when($page && $limit, function ($query) use ($page, $limit) {
            $query->page($page, $limit);
        })->order('parts_id desc')->select();

        $count = $list->count();
        $total = ProductParts::count();
        return compact('list', 'count', 'total');
    }

    /**
     * 保存
     * @param int|null $id
     * @param array $data
     * @return void
     */
    public function save(?int $id, array $data): ProductParts
    {
        if ($id) {
            unset($data['parts_id']);
            $model = ProductParts::update($data, ['parts_id' => $id]);
        } else {
            $model = ProductParts::create($data);
        }

        if (!$model) {
            throw new AdminException(100006);
        }
        return $model;
    }

    /**
     * 计算已选配件的加价总额
     * @param array|null $parts_list 商品配件已选列表
     * @return string
     */
    public static function sumPartsPrice(?array $parts_list): string
    {
        $parts_price = '0';
        if (empty($parts_list)) {
            return $parts_price;
        }

        foreach ($parts_list as $item) {
            $parts_price += array_sum(array_column($item['parts'], 'parts_price'));
        }
        return $parts_price;
    }

    /**
     * 校验配件是否有变动
     * @param int $productId
     * @param array|null $parts_list 商品配件已选列表
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function verification(int $productId, ?array $parts_list): bool
    {
        // 校验商品
        /** @var StoreProduct $product */
        $product = StoreProduct::findOrEmpty($productId);
        if ($product->isEmpty()) {
            return false;
        }

        // 校验布尔值
        if (empty($product->parts_ids) !== empty($parts_list)) {
            return false;
        }

        // 校验商品关联的配件
        $parts_ids = empty($product->parts_ids) ? [] : explode(',', $product->parts_ids);
        if (empty($parts_ids)) {
            return true;
        }

        // 校验配件数组长度
        if (count($parts_ids) !== count($parts_list)) {
            return false;
        }

        // 校验配件
        $_parts_ids = array_column($parts_list, 'parts_id');
        sort($parts_ids, SORT_NUMERIC);
        sort($_parts_ids, SORT_NUMERIC);
        if (implode(',', $_parts_ids) !== implode(',', $parts_ids)) {
            return false;
        }
        $_parts_list = array_column($parts_list, null, 'parts_id');
        $list = ProductParts::getList($parts_ids);
        if ($list->isEmpty()) {
            return false;
        }

        try {
            $list->each(function (ProductParts $model) use ($_parts_list) {
                $parts_id = $model->parts_id;
                // 检查配件
                if ($model->title != $_parts_list[$parts_id]['title']) {
                    throw new RuntimeException("商品配件{$parts_id}名称不相同");
                }
                if ($model->multiple != $_parts_list[$parts_id]['multiple']) {
                    throw new RuntimeException("商品配件{$parts_id}可选上限数量不相同");
                }
                if ($model->multiple_min != $_parts_list[$parts_id]['multiple_min']) {
                    throw new RuntimeException("商品配件{$parts_id}可选下限数量不相同");
                }
                // 检查配件值verify_hash
                $parts = $model['parts'] ?? null;
                if (empty($parts)) {
                    throw new RuntimeException("当前商品的配件{$parts_id}列表值为空");
                } else {
                    $parts = json_decode($parts, true);
                }
                $_parts = $_parts_list[$parts_id]['parts'];
                if (empty($_parts)) {
                    throw new RuntimeException("目标商品的配件{$parts_id}列表值为空");
                }

                // 校验复选数量
                $selfCount = count($_parts);
                if ($selfCount < $model->multiple_min || $model->multiple < $selfCount) {
                    throw new RuntimeException("目标商品的配件{$parts_id}，可选{$model->multiple_min}-{$model->multiple}个，当前选中{$selfCount}个");
                }

                $verify_hash = array_column($parts, 'verify_hash');
                $_verify_hash = array_column($_parts, 'verify_hash');
                if (!empty(array_diff($_verify_hash, $verify_hash))) {
                    throw new RuntimeException("目标商品配件{$parts_id}值的差集不为空");
                }
                if (!empty(array_diff(array_intersect($verify_hash, $_verify_hash), $_verify_hash))) {
                    throw new RuntimeException("当前商品配件{$parts_id}值的差集不为空");
                }
            });
        } catch (Throwable $throwable) {
            Log::record("校验商品配件失败，商品ID：{$productId} | 详情：" . $throwable->getMessage());
            return false;
        }

        return true;
    }

    /**
     * 获取一条数据
     * @param int $id
     * @return ProductParts|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function read(int $id): ?ProductParts
    {
        /** @var ProductParts|null $model */
        $model = ProductParts::with(ProductParts::withParts())->find($id);
        return $model;
    }

    /**
     * 删除
     * @param array $ids
     * @return bool
     */
    public function del(array $ids): bool
    {
        return ProductParts::destroy($ids);
    }
}
