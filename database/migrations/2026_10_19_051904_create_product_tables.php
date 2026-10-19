<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 商品域：商品分类、品牌、商品、SKU、库存流水
     */
    public function up(): void
    {
        // 商品分类表：树形，与内容栏目分离
        Schema::create('product_categories', function (Blueprint $table) {
            $table->comment('商品分类表');
            $table->id();
            $table->unsignedInteger('parent_id')->default(0)->comment('父级ID,0=顶级');
            $table->string('name', 50)->comment('分类名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->index('parent_id', 'idx_product_categories_parent_id');
        });

        // 品牌表
        Schema::create('product_brands', function (Blueprint $table) {
            $table->comment('品牌表');
            $table->id();
            $table->string('name', 50)->comment('品牌名称');
            $table->string('logo', 255)->nullable()->comment('品牌LOGO');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->unique('name', 'uk_brands_name');
        });

        // 商品表
        Schema::create('products', function (Blueprint $table) {
            $table->comment('商品表');
            $table->id();
            $table->unsignedInteger('category_id')->comment('分类ID');
            $table->unsignedInteger('brand_id')->nullable()->comment('品牌ID');
            $table->string('name', 200)->comment('商品名称');
            $table->string('sn', 50)->nullable()->comment('商品编码');
            $table->string('subtitle', 200)->nullable()->comment('副标题');
            $table->string('cover', 255)->nullable()->comment('主图');
            $table->text('images')->nullable()->comment('图册(JSON数组)');
            $table->longText('content')->nullable()->comment('商品详情');
            $table->decimal('price', 10, 2)->comment('售价,多规格时为最低规格价');
            $table->decimal('market_price', 10, 2)->nullable()->comment('市场价');
            $table->unsignedInteger('stock')->default(0)->comment('库存,单规格商品有效');
            $table->unsignedInteger('sales')->default(0)->comment('销量');
            $table->tinyInteger('status')->default(0)->comment('状态:0=下架,1=上架');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('sn', 'uk_products_sn');
            $table->index(['category_id', 'status'], 'idx_products_category_status');
            $table->index('brand_id', 'idx_products_brand_id');
        });

        // 商品SKU表：多规格商品的价格与库存载体
        Schema::create('product_skus', function (Blueprint $table) {
            $table->comment('商品SKU表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->string('specs', 500)->comment('规格值(JSON),如:{"颜色":"红","尺码":"L"}');
            $table->decimal('price', 10, 2)->comment('售价');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->index('product_id', 'idx_product_skus_product_id');
        });

        // 库存流水表：下单占用/取消释放/退货回补/人工调整，账实不符时用于追溯
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->comment('库存流水表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格商品');
            $table->string('change_type', 20)->comment('变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整');
            $table->integer('change_quantity')->comment('变动数量,正数入库负数出库');
            $table->integer('before_stock')->comment('变动前库存');
            $table->integer('after_stock')->comment('变动后库存');
            $table->unsignedInteger('related_id')->nullable()->comment('关联单据ID(订单/退款单)');
            $table->string('user_type', 20)->nullable()->comment('操作账户类型:admin,user,system');
            $table->unsignedInteger('user_id')->nullable()->comment('操作人ID');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['product_id', 'sku_id'], 'idx_product_stock_logs_product_sku');
            $table->index('related_id', 'idx_product_stock_logs_related_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_logs');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('product_categories');
    }
};
