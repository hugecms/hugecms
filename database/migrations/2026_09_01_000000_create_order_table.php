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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // order_item	订单明细（每条 SKU）	order_id, sku_id, sku_name, sku_image, price, quantity, total_price
        // order_payment	支付流水记录	order_id, payment_method, transaction_id, pay_time, amount, status
        // order_shipment	发货物流信息	order_id, logistics_company, tracking_number, ship_time, receive_time
        // order_refund	售后/退款单	order_item_id, refund_sn, refund_amount, reason, status (待审核/通过/拒绝)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
