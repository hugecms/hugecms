<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 营销域：优惠券（类型/用户领取）、促销活动（满减/折扣）
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
            $table->unsignedInteger('user_id')->nullable()->comment('持有用户ID,未发放为空');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_promotions');
        Schema::dropIfExists('marketing_user_coupons');
        Schema::dropIfExists('marketing_coupons');
    }
};
