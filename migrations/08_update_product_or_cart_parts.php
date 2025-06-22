<?php

use Ledc\DeliverySlotBooking\model\ProductParts;
use Phinx\Db\Adapter\AdapterInterface;
use think\migration\Migrator;

/**
 * 修改商品表和购物车表
 */
class UpdateProductOrCartParts extends Migrator
{
    /**
     * Change Method.
     */
    public function change()
    {
        $this->table('store_product')
            ->addColumn(ProductParts::PRODUCT_FIELD, AdapterInterface::PHINX_TYPE_STRING, ['comment' => '商品配件', 'null' => true, 'limit' => 300])
            ->update();

        $this->table('store_cart')
            ->addColumn(ProductParts::CART_PARTS_LIST, AdapterInterface::PHINX_TYPE_TEXT, ['comment' => '商品配件', 'null' => true])
            ->addColumn(ProductParts::CART_PARTS_MD5, AdapterInterface::PHINX_TYPE_CHAR, ['comment' => '商品配件哈希', 'limit' => 32, 'null' => false, 'default' => ''])
            ->addIndex([ProductParts::CART_PARTS_MD5], ['name' => 'idx_parts_md5'])
            ->update();
    }
}
