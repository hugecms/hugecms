<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 营销域：优惠券（类型/用户领取）、促销活动（满减/折扣）、秒杀/限时购、分销返佣（两级分销）
     * 秒杀库存走 sold_stock 原子自增（where sold_stock + n <= total_stock），订单取消回补，变动记 marketing_flash_sale_logs
     * 佣金发放走 user_account_logs 入账，提现复用 user_accounts(withdraw)；分销比例/结算条件配置于 system_settings
     */
    public function up(): void
    {
        // 优惠券类型表
        Schema::create('marketing_coupons', function (Blueprint $table) {
            $table->comment('优惠券类型表');
            $table->id();
            $table->string('name', 50)->comment('券名称');
            $table->string('type', 20)->comment('类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('使用门槛,0=无门槛');
            $table->decimal('reduce_amount', 10, 2)->nullable()->comment('满减金额,满减券必填');
            $table->decimal('discount_rate', 3, 1)->nullable()->comment('折扣率,如8.5表示85折,折扣券必填');
            $table->string('receive_type', 20)->default('manual')->comment('发放方式:manual=后台发放,order=下单赠送,register=注册赠送');
            $table->unsignedInteger('total')->default(0)->comment('发放总量,0=不限');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人限领');
            $table->dateTime('start_time')->nullable()->comment('有效期开始,空=领取即生效');
            $table->dateTime('end_time')->nullable()->comment('有效期结束');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
        });

        // 用户优惠券表：券的实例，领取与核销记录
        Schema::create('marketing_user_coupons', function (Blueprint $table) {
            $table->comment('用户优惠券表');
            $table->id();
            $table->unsignedInteger('coupon_id')->comment('优惠券类型ID');
            $table->unsignedInteger('user_id')->comment('持有用户ID,发放即创建归属');
            $table->unsignedInteger('order_id')->nullable()->comment('核销订单ID');
            $table->tinyInteger('status')->default(0)->comment('状态:0=未使用,1=已使用,2=已过期');
            $table->dateTime('used_at')->nullable()->comment('核销时间');
            $table->dateTime('expire_at')->nullable()->comment('过期时间,空=按券类型有效期');
            $table->timestamps();
            $table->index('coupon_id', 'idx_marketing_user_coupons_coupon_id');
            $table->index(['user_id', 'status'], 'idx_marketing_user_coupons_user_status');
        });

        // 促销活动表：满减/折扣，支持按范围生效
        Schema::create('marketing_promotions', function (Blueprint $table) {
            $table->comment('促销活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->string('type', 20)->comment('类型:full_reduce=满减,discount=折扣');
            $table->string('range_type', 20)->default('all')->comment('适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品');
            $table->json('range_values')->nullable()->comment('范围内ID列表(JSON),all 时为空');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('门槛金额,0=无门槛');
            $table->decimal('reduce_amount', 10, 2)->nullable()->comment('满减金额,满减活动必填');
            $table->decimal('discount_rate', 3, 1)->nullable()->comment('折扣率,如8.5表示85折,折扣活动必填');
            $table->dateTime('start_time')->nullable()->comment('活动开始时间');
            $table->dateTime('end_time')->nullable()->comment('活动结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
        });

        // 秒杀活动表：按时间段组织，同时间段内可含多个场次商品
        Schema::create('marketing_flash_sales', function (Blueprint $table) {
            $table->comment('秒杀活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->dateTime('start_time')->comment('开始时间');
            $table->dateTime('end_time')->comment('结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['status', 'start_time', 'end_time'], 'idx_marketing_flash_sales_status_time');
        });

        // 秒杀商品表：活动维度的特价与限量，可购=total_stock-sold_stock；价格优先级:秒杀价>阶梯价>会员价>会员折扣
        Schema::create('marketing_flash_sale_items', function (Blueprint $table) {
            $table->comment('秒杀商品表');
            $table->id();
            $table->unsignedInteger('sale_id')->comment('秒杀活动ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格');
            $table->decimal('original_price', 10, 2)->comment('原价快照');
            $table->decimal('price', 10, 2)->comment('秒杀价');
            $table->unsignedInteger('total_stock')->comment('秒杀总量');
            $table->unsignedInteger('sold_stock')->default(0)->comment('已售量,可购=total_stock-sold_stock');
            $table->unsignedInteger('per_limit')->default(1)->comment('每人限购');
            $table->integer('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=下架,1=上架');
            $table->timestamps();
            $table->unique(['sale_id', 'product_id', 'sku_id'], 'uk_marketing_flash_sale_items_sale_product_sku');
            $table->index(['product_id', 'status'], 'idx_marketing_flash_sale_items_product_status');
        });

        // 分销员表：用户申请成为分销员，审核通过后可推广计佣
        Schema::create('marketing_distribution_users', function (Blueprint $table) {
            $table->comment('分销员表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('inviter_id')->default(0)->comment('上级分销员用户ID,0=无上级');
            $table->tinyInteger('level')->default(1)->comment('分销员等级:1=普通,2=高级');
            $table->decimal('total_commission', 10, 2)->default(0)->comment('累计佣金');
            $table->decimal('settled_commission', 10, 2)->default(0)->comment('已结算佣金,可提现=total-settled');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审核,1=通过,2=拒绝,3=冻结');
            $table->timestamps();
            $table->unique('user_id', 'uk_marketing_distribution_users_user_id');
            $table->index('inviter_id', 'idx_marketing_distribution_users_inviter_id');
        });

        // 分销绑定关系表：下单人与分销员的层级快照，绑定后下单即计佣，防层级变更漏算
        Schema::create('marketing_distribution_relations', function (Blueprint $table) {
            $table->comment('分销绑定关系表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('被绑定用户ID(下单人)');
            $table->unsignedInteger('distributor_id')->comment('分销员用户ID');
            $table->tinyInteger('level')->comment('层级:1=一级(直接邀请),2=二级(间接)');
            $table->timestamp('create_time')->useCurrent()->comment('绑定时间');
            $table->unique(['user_id', 'distributor_id'], 'uk_marketing_distribution_relations_user_distributor');
            $table->index('distributor_id', 'idx_marketing_distribution_relations_distributor_id');
        });

        // 分销佣金流水表：订单确认完成后结算，退款将佣金置为已失效
        Schema::create('marketing_distribution_commissions', function (Blueprint $table) {
            $table->comment('分销佣金流水表');
            $table->id();
            $table->unsignedInteger('distributor_id')->comment('分销员用户ID');
            $table->unsignedInteger('user_id')->comment('购买用户ID');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('order_item_id')->nullable()->comment('订单明细ID,空=整单计佣');
            $table->tinyInteger('level')->comment('层级:1=一级,2=二级');
            $table->decimal('order_amount', 10, 2)->comment('计佣基数(商品实付)');
            $table->decimal('rate', 5, 2)->comment('佣金比例(%),如5.00表示5%');
            $table->decimal('amount', 10, 2)->comment('佣金金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待结算,1=已结算,2=已失效(退款)');
            $table->dateTime('settled_at')->nullable()->comment('结算时间');
            $table->timestamps();
            $table->index(['distributor_id', 'status'], 'idx_marketing_distribution_commissions_distributor_status');
            $table->index('order_id', 'idx_marketing_distribution_commissions_order_id');
        });

        // 秒杀库存流水表：sold_stock 每次原子变动的审计留痕，超卖/回补纠纷追溯依据
        Schema::create('marketing_flash_sale_logs', function (Blueprint $table) {
            $table->comment('秒杀库存流水表');
            $table->id();
            $table->unsignedInteger('flash_sale_item_id')->comment('秒杀商品ID,关联marketing_flash_sale_items');
            $table->unsignedInteger('order_id')->comment('关联订单ID,扣减与回补均关联订单');
            $table->integer('change_quantity')->comment('变动数量,正数扣减,负数回补');
            $table->unsignedInteger('before_sold_stock')->comment('变动前已售量');
            $table->unsignedInteger('after_sold_stock')->comment('变动后已售量');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('flash_sale_item_id', 'idx_marketing_flash_sale_logs_item');
            $table->index('order_id', 'idx_marketing_flash_sale_logs_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_flash_sale_logs');
        Schema::dropIfExists('marketing_distribution_commissions');
        Schema::dropIfExists('marketing_distribution_relations');
        Schema::dropIfExists('marketing_distribution_users');
        Schema::dropIfExists('marketing_flash_sale_items');
        Schema::dropIfExists('marketing_flash_sales');
        Schema::dropIfExists('marketing_promotions');
        Schema::dropIfExists('marketing_user_coupons');
        Schema::dropIfExists('marketing_coupons');
    }
};
