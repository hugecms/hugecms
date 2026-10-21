<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 营销域：优惠券（类型/用户领取）、促销活动（满减/折扣，范围与阶梯拆子表）、秒杀/限时购、分销返佣（两级分销）
     * 促销范围/优惠参数为 promotion_id 关联子表（marketing_promotion_scopes/marketing_promotion_ladders），保存整体替换，算价引擎按 ladder 逐档匹配
     * 秒杀库存走 sold_stock 原子自增（where sold_stock + n <= total_stock），订单取消回补，变动记 marketing_flash_sale_logs
     * 佣金按 marketing_commission_rules（商品>分类>system_settings 全局兜底）计佣，保护期后结算入账 user_accounts（biz_type=1），
     * 保护期内退款置失效，已结算退款走负额冲销记录（reversal_of_id 回指，唯一索引允许多 NULL 即原始记录互不冲突）；提现走 user_withdraw_orders 状态机
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

        // 促销活动表：满减/折扣主档，范围与优惠参数拆子表
        Schema::create('marketing_promotions', function (Blueprint $table) {
            $table->comment('促销活动表');
            $table->id();
            $table->string('name', 100)->comment('活动名称');
            $table->string('type', 20)->comment('类型:full_reduce=满减,discount=折扣');
            $table->string('range_type', 20)->default('all')->comment('适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品');
            $table->dateTime('start_time')->nullable()->comment('活动开始时间');
            $table->dateTime('end_time')->nullable()->comment('活动结束时间');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->index(['status', 'start_time', 'end_time'], 'idx_marketing_promotions_status_time');
        });

        // 促销适用范围表：range_type=all 时无行，其余按 scope_type 逐条圈定
        Schema::create('marketing_promotion_scopes', function (Blueprint $table) {
            $table->comment('促销适用范围表');
            $table->id();
            $table->unsignedInteger('promotion_id')->comment('促销活动ID,关联marketing_promotions');
            $table->string('scope_type', 20)->comment('范围类型:category=分类,brand=品牌,product=商品,与父表range_type一致');
            $table->unsignedInteger('target_id')->comment('目标ID,按scope_type指向分类/品牌/商品');
            $table->timestamps();
            $table->unique(['promotion_id', 'scope_type', 'target_id'], 'uk_marketing_promotion_scopes_promotion_scope_target');
            $table->index(['scope_type', 'target_id'], 'idx_marketing_promotion_scopes_scope_target');
        });

        // 促销满减阶梯表：单档=无阶梯，多档=满减阶梯（如满100-10/满200-30）；优惠参数只存本表
        Schema::create('marketing_promotion_ladders', function (Blueprint $table) {
            $table->comment('促销满减阶梯表');
            $table->id();
            $table->unsignedInteger('promotion_id')->comment('促销活动ID,关联marketing_promotions');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('门槛金额,0=无门槛');
            $table->decimal('reduce_amount', 10, 2)->nullable()->comment('满减金额,full_reduce类型必填');
            $table->decimal('discount_rate', 3, 1)->nullable()->comment('折扣率,如8.5表示85折,discount类型必填');
            $table->timestamps();
            $table->unique(['promotion_id', 'min_amount'], 'uk_marketing_promotion_ladders_promotion_min_amount');
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

        // 佣金规则表：分类默认/商品覆盖两级比例，命中优先级 商品>分类>system_settings 全局兜底
        Schema::create('marketing_commission_rules', function (Blueprint $table) {
            $table->comment('佣金规则表');
            $table->id();
            $table->tinyInteger('scope_type')->comment('范围类型:1=分类默认,2=商品覆盖');
            $table->unsignedInteger('scope_id')->comment('目标ID,scope_type=1指向分类,2指向商品');
            $table->decimal('level1_rate', 5, 2)->comment('一级佣金比例(%),如5.00表示5%');
            $table->decimal('level2_rate', 5, 2)->comment('二级佣金比例(%)');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->timestamps();
            $table->unique(['scope_type', 'scope_id'], 'uk_marketing_commission_rules_scope_type_id');
            $table->index('status', 'idx_marketing_commission_rules_status');
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

        // 分销佣金流水表：订单完成后按保护期生成待结算记录，到期结算入账 user_accounts
        // 保护期内退款置失效（未动资金）；已结算退款走负额冲销记录（reversal_of_id 回指原记录，唯一索引允许多 NULL 即原始记录互不冲突）
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
            $table->decimal('amount', 10, 2)->comment('佣金金额,冲销记录为负值');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款,原记录侧)');
            $table->dateTime('settled_at')->nullable()->comment('实际结算入账时间');
            $table->dateTime('settle_time')->nullable()->comment('应结算时间=订单完成时间+保护期,到期由结算流程入账');
            $table->unsignedInteger('reversal_record_id')->default(0)->comment('冲销记录ID,0=未被冲销,原记录侧回指负额冲销记录');
            $table->unsignedInteger('reversal_of_id')->nullable()->comment('原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录');
            $table->timestamps();
            $table->unique('reversal_of_id', 'uk_marketing_distribution_commissions_reversal_of');
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
        Schema::dropIfExists('marketing_commission_rules');
        Schema::dropIfExists('marketing_flash_sale_items');
        Schema::dropIfExists('marketing_flash_sales');
        Schema::dropIfExists('marketing_promotion_ladders');
        Schema::dropIfExists('marketing_promotion_scopes');
        Schema::dropIfExists('marketing_promotions');
        Schema::dropIfExists('marketing_user_coupons');
        Schema::dropIfExists('marketing_coupons');
    }
};
