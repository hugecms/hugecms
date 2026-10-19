<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateProductTables extends Migrator
{
    /**
     * 商品域：商品分类、品牌、商品、SKU、库存流水
     */
    public function change()
    {
        // 商品分类表：树形，与内容栏目分离
        $productCategories = $this->table('product_categories', ['comment' => '商品分类表']);
        $productCategories->addColumn(Column::unsignedInteger('parent_id')->setNull(false)->setDefault(0)->setComment('父级ID,0=顶级'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('分类名称'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('parent_id', ['name' => 'idx_product_categories_parent_id'])
            ->create();

        // 品牌表
        $brands = $this->table('product_brands', ['comment' => '品牌表']);
        $brands->addColumn(Column::string('name', 50)->setNull(false)->setComment('品牌名称'))
            ->addColumn(Column::string('logo', 255)->setNullable()->setComment('品牌LOGO'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('name', ['unique' => true, 'name' => 'uk_brands_name'])
            ->create();

        // 商品表
        $products = $this->table('products', ['comment' => '商品表']);
        $products->addColumn(Column::unsignedInteger('category_id')->setNull(false)->setComment('分类ID'))
            ->addColumn(Column::unsignedInteger('brand_id')->setNullable()->setComment('品牌ID'))
            ->addColumn(Column::string('name', 200)->setNull(false)->setComment('商品名称'))
            ->addColumn(Column::string('sn', 50)->setNullable()->setComment('商品编码'))
            ->addColumn(Column::string('subtitle', 200)->setNullable()->setComment('副标题'))
            ->addColumn(Column::string('cover', 255)->setNullable()->setComment('主图'))
            ->addColumn(Column::text('images')->setNullable()->setComment('图册(JSON数组)'))
            ->addColumn(Column::longText('content')->setNullable()->setComment('商品详情'))
            ->addColumn(Column::decimal('price', 10, 2)->setNull(false)->setComment('售价,多规格时为最低规格价'))
            ->addColumn(Column::decimal('market_price', 10, 2)->setNullable()->setComment('市场价'))
            ->addColumn(Column::unsignedInteger('stock')->setNull(false)->setDefault(0)->setComment('库存,单规格商品有效'))
            ->addColumn(Column::unsignedInteger('sales')->setNull(false)->setDefault(0)->setComment('销量'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=下架,1=上架'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('sn', ['unique' => true, 'name' => 'uk_products_sn'])
            ->addIndex(['category_id', 'status'], ['name' => 'idx_products_category_status'])
            ->addIndex('brand_id', ['name' => 'idx_products_brand_id'])
            ->create();

        // 商品SKU表：多规格商品的价格与库存载体
        $productSkus = $this->table('product_skus', ['comment' => '商品SKU表']);
        $productSkus->addColumn(Column::unsignedInteger('product_id')->setNull(false)->setComment('商品ID'))
            ->addColumn(Column::string('specs', 500)->setNull(false)->setComment('规格值(JSON),如:{"颜色":"红","尺码":"L"}'))
            ->addColumn(Column::decimal('price', 10, 2)->setNull(false)->setComment('售价'))
            ->addColumn(Column::unsignedInteger('stock')->setNull(false)->setDefault(0)->setComment('库存'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=禁用,1=启用'))
            ->addTimestamps()
            ->addIndex('product_id', ['name' => 'idx_product_skus_product_id'])
            ->create();

        // 库存流水表：下单占用/取消释放/退货回补/人工调整，账实不符时用于追溯
        $stockLogs = $this->table('product_stock_logs', ['comment' => '库存流水表']);
        $stockLogs->addColumn(Column::unsignedInteger('product_id')->setNull(false)->setComment('商品ID'))
            ->addColumn(Column::unsignedInteger('sku_id')->setNull(false)->setDefault(0)->setComment('SKU ID,0=单规格商品'))
            ->addColumn(Column::string('change_type', 20)->setNull(false)->setComment('变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整'))
            ->addColumn(Column::integer('change_quantity')->setNull(false)->setComment('变动数量,正数入库负数出库'))
            ->addColumn(Column::integer('before_stock')->setNull(false)->setComment('变动前库存'))
            ->addColumn(Column::integer('after_stock')->setNull(false)->setComment('变动后库存'))
            ->addColumn(Column::unsignedInteger('related_id')->setNullable()->setComment('关联单据ID(订单/退款单)'))
            ->addColumn(Column::string('user_type', 20)->setNullable()->setComment('操作账户类型:admin,user,system'))
            ->addColumn(Column::unsignedInteger('user_id')->setNullable()->setComment('操作人ID'))
            ->addColumn(Column::string('remark', 255)->setNullable()->setComment('备注'))
            ->addTimestamps('create_time', null)
            ->addIndex(['product_id', 'sku_id'], ['name' => 'idx_product_stock_logs_product_sku'])
            ->addIndex('related_id', ['name' => 'idx_product_stock_logs_related_id'])
            ->create();
    }
}
