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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        // cart	用户购物车（未登录用设备ID）	id, user_id / device_id, sku_id, quantity, is_checked
        // cart_setting	购物车设置（如展示价格）	user_id, show_price_type, auto_add_coupon
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
