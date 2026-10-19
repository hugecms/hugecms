<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 支付域：通用支付单 + 支付渠道配置
     * 支付单与业务解耦：biz_type/biz_id 关联订单支付或余额充值（user_accounts）
     */
    public function up(): void
    {
        // 支付单表：订单支付/充值共用，同一业务单可多次支付尝试
        Schema::create('payments', function (Blueprint $table) {
            $table->comment('支付单表');
            $table->id();
            $table->string('payment_no', 30)->comment('支付单号');
            $table->unsignedInteger('user_id')->comment('付款用户ID');
            $table->string('biz_type', 20)->comment('业务类型:order=订单支付,recharge=余额充值');
            $table->unsignedInteger('biz_id')->comment('业务单据ID,如:订单ID/充值申请ID');
            $table->string('pay_method', 20)->comment('支付方式:wechat,alipay,balance');
            $table->decimal('amount', 10, 2)->comment('支付金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待支付,1=成功,2=失败');
            $table->string('trade_no', 64)->nullable()->comment('第三方交易号');
            $table->dateTime('paid_at')->nullable()->comment('支付成功时间');
            $table->timestamps();
            $table->unique('payment_no', 'uk_payments_payment_no');
            $table->index(['biz_type', 'biz_id'], 'idx_payments_biz');
            $table->index('trade_no', 'idx_payments_trade_no');
        });

        // 支付渠道配置表：商户号/密钥等渠道参数
        Schema::create('payment_channels', function (Blueprint $table) {
            $table->comment('支付渠道配置表');
            $table->id();
            $table->string('code', 20)->comment('渠道标识:wechat,alipay,balance');
            $table->string('name', 50)->comment('渠道名称');
            $table->json('config')->nullable()->comment('渠道配置(JSON):商户号/密钥等');
            $table->tinyInteger('status')->default(1)->comment('状态:0=停用,1=启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            $table->unique('code', 'uk_payment_channels_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_channels');
        Schema::dropIfExists('payments');
    }
};
