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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // payment_channel	支付渠道配置	id, channel (alipay/wechat), app_id, merchant_id, public_key, private_key
        // payment_transaction	支付交易流水（全局唯一）	id, order_sn, channel, transaction_no (渠道流水号), amount, status
        // payment_callback_log	回调通知日志（用于排查掉单）	transaction_id, callback_data, receive_time, is_processed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
