<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 商品域：分类、品牌、商品、SKU、库存流水、属性体系、价格策略、商品评价
     * 商品评价独立于 content_comments 通用评论：需星级评分、关联订单明细做已购验证
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
            $table->tinyInteger('is_recommend')->default(0)->comment('首页推荐:0=否,1=是');
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
            $table->unique('name', 'uk_product_brands_name');
        });

        // 商品表
        Schema::create('products', function (Blueprint $table) {
            $table->comment('商品表');
            $table->id();
            $table->unsignedInteger('category_id')->comment('分类ID');
            $table->unsignedInteger('brand_id')->nullable()->comment('品牌ID');
            $table->unsignedInteger('type_id')->nullable()->comment('商品类型ID,关联属性体系');
            $table->string('name', 200)->comment('商品名称');
            $table->string('sn', 50)->nullable()->comment('商品编码');
            $table->string('subtitle', 200)->nullable()->comment('副标题');
            $table->string('cover', 255)->nullable()->comment('主图');
            $table->json('images')->nullable()->comment('图册(JSON数组)');
            $table->longText('content')->nullable()->comment('商品详情');
            $table->decimal('price', 10, 2)->comment('售价,多规格时为最低规格价');
            $table->decimal('market_price', 10, 2)->nullable()->comment('市场价');
            $table->unsignedInteger('sales')->default(0)->comment('销量');
            $table->tinyInteger('status')->default(0)->comment('状态:0=下架,1=上架');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('sn', 'uk_products_sn');
            $table->index(['category_id', 'status'], 'idx_products_category_status');
            $table->index('brand_id', 'idx_products_brand_id');
        });

        // 商品SKU表：多规格商品的价格与库存载体，跟随商品软删级联（商品软删/恢复时应用层同步处理）
        Schema::create('product_skus', function (Blueprint $table) {
            $table->comment('商品SKU表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->string('specs', 500)->comment('规格值(JSON),如:{"颜色":"红","尺码":"L"}');
            $table->string('sku_no', 64)->nullable()->comment('SKU编号');
            $table->string('barcode', 64)->nullable()->comment('条码,仓储/扫码出入库');
            $table->string('image', 255)->nullable()->comment('SKU图片');
            $table->decimal('price', 10, 2)->comment('售价');
            $table->decimal('line_price', 10, 2)->nullable()->comment('划线价(市场价展示)');
            $table->decimal('cost_price', 10, 2)->default(0)->comment('成本价,毛利核算');
            $table->decimal('weight', 10, 3)->default(0)->comment('重量(kg),运费计算');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->unsignedInteger('warn_stock')->default(0)->comment('库存预警线,库存<=该值触发预警,0=不预警');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('sku_no', 'uk_product_skus_sku_no');
            $table->index('barcode', 'idx_product_skus_barcode');
            $table->index('product_id', 'idx_product_skus_product_id');
        });

        // 库存流水表：下单占用/取消释放/退货回补/人工调整，账实不符时用于追溯
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->comment('库存流水表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->comment('SKU ID,关联product_skus');
            $table->string('change_type', 20)->comment('变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整');
            $table->integer('change_quantity')->comment('变动数量,正数入库负数出库');
            $table->integer('before_stock')->comment('变动前库存');
            $table->integer('after_stock')->comment('变动后库存');
            $table->unsignedInteger('related_id')->nullable()->comment('关联单据ID(订单/退款单)');
            $table->string('user_type', 20)->nullable()->comment('操作账户类型:admin=后台,user=前台,system=系统');
            $table->unsignedInteger('user_id')->nullable()->comment('操作人ID');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index(['product_id', 'sku_id'], 'idx_product_stock_logs_product_sku');
            $table->index('related_id', 'idx_product_stock_logs_related_id');
        });

        // 商品类型表：属性集容器，如"手机""服装"，商品挂靠类型后启用属性
        Schema::create('product_types', function (Blueprint $table) {
            $table->comment('商品类型表');
            $table->id();
            $table->string('name', 50)->comment('类型名称,如:手机');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
        });

        // 商品属性定义表：规格属性参与 SKU 组合，普通属性仅作展示与筛选
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->comment('商品属性定义表');
            $table->id();
            $table->unsignedInteger('type_id')->comment('所属商品类型ID');
            $table->string('name', 50)->comment('属性名,如:颜色');
            $table->tinyInteger('input_type')->default(1)->comment('录入方式:1=手工输入,2=列表选择');
            $table->string('values', 500)->nullable()->comment('可选值列表(逗号分隔),列表选择时用');
            $table->tinyInteger('is_spec')->default(0)->comment('是否规格属性:0=否,1=是(参与SKU组合)');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamps();
            $table->index('type_id', 'idx_product_attributes_type_id');
        });

        // 商品属性值表：商品的属性快照，规格属性值可关联 SKU
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->comment('商品属性值表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('attribute_id')->comment('属性ID');
            $table->string('value', 255)->comment('属性值');
            $table->unsignedInteger('sku_id')->nullable()->comment('关联SKU ID,规格属性时对应');
            $table->timestamps();
            $table->unique(['product_id', 'attribute_id', 'value'], 'uk_product_attribute_values_product_attr_value');
            $table->index('attribute_id', 'idx_product_attribute_values_attribute_id');
        });

        // 商品会员等级价表：覆盖 products.price，会员价优先级高于等级折扣
        Schema::create('product_member_prices', function (Blueprint $table) {
            $table->comment('商品会员等级价表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('rank_id')->comment('会员等级ID');
            $table->decimal('price', 10, 2)->comment('等级价');
            $table->timestamps();
            $table->unique(['product_id', 'rank_id'], 'uk_product_member_prices_product_rank');
        });

        // 商品阶梯价表：按购买数量分档定价，命中最低档优于会员价
        Schema::create('product_volume_prices', function (Blueprint $table) {
            $table->comment('商品阶梯价表');
            $table->id();
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=商品统一价');
            $table->unsignedInteger('min_quantity')->comment('起购数量');
            $table->decimal('price', 10, 2)->comment('单价');
            $table->timestamps();
            $table->unique(['product_id', 'sku_id', 'min_quantity'], 'uk_product_volume_prices_product_sku_qty');
        });

        // 商品评价表：一个订单明细仅可评价一次，rating 1-5 星，支持晒图/追评/商家回复
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->comment('商品评价表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID,已购验证依据');
            $table->unsignedInteger('order_item_id')->comment('订单明细ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->comment('SKU ID,关联product_skus');
            $table->unsignedInteger('user_id')->comment('评价用户ID');
            $table->tinyInteger('rating')->comment('评分:1-5星');
            $table->string('content', 1000)->nullable()->comment('评价内容');
            $table->json('images')->nullable()->comment('晒图(JSON数组)');
            $table->string('append_content', 1000)->nullable()->comment('追评内容');
            $table->dateTime('appended_at')->nullable()->comment('追评时间');
            $table->string('reply', 500)->nullable()->comment('商家回复');
            $table->dateTime('replied_at')->nullable()->comment('商家回复时间');
            $table->tinyInteger('is_anonymous')->default(0)->comment('匿名:0=否,1=是');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审,1=通过,2=拒绝');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['order_item_id', 'user_id'], 'uk_product_reviews_item_user');
            $table->index(['product_id', 'status'], 'idx_product_reviews_product_status');
            $table->index('user_id', 'idx_product_reviews_user_id');
        });

        // 到货提醒表：缺货登记与到货通知，一用户一SKU 一条复用（再次登记 upsert 重置状态）；到货后走触达降级链路提醒
        Schema::create('product_stock_reminders', function (Blueprint $table) {
            $table->comment('到货提醒表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('登记用户ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=不限规格');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待提醒,1=已提醒,2=已取消');
            $table->dateTime('notified_at')->nullable()->comment('提醒时间');
            $table->timestamps();
            $table->unique(['user_id', 'product_id', 'sku_id'], 'uk_product_stock_reminders_user_product_sku');
            $table->index(['product_id', 'status'], 'idx_product_stock_reminders_product_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_reminders');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_volume_prices');
        Schema::dropIfExists('product_member_prices');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('product_stock_logs');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('product_categories');
    }
};
