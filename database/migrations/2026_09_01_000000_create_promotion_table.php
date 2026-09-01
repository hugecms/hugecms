<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        //         // promotion	促销活动主表	id, name, type (满减/折扣/秒杀), start_time, end_time, status
        // promotion_rule	促销规则（阶梯或条件）	promotion_id, min_amount, discount_amount, discount_rate
        // promotion_product	活动参与商品范围	promotion_id, product_id / category_id (全店或指定)
        // coupon	优惠券批次定义	id, name, type, discount_value, total_quantity, used_quantity
        // user_coupon	用户领取的优惠券（多对多关系）	user_id, coupon_id, get_time, use_time, order_id, status (未使用/已使用/已过期)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion');
    }
};
