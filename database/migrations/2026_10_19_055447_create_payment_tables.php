<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 支付域：通用支付单、支付渠道配置、回调流水留痕
     * 支付单与业务解耦：biz_type/biz_id 关联订单支付或余额充值（user_accounts）
     * 幂等：回调按 unique(channel,notify_id) 去重，单据状态流转用条件更新（where status=0）
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

        // 支付回调流水表：第三方异步通知原始报文留痕，对账/重放排查依据
        Schema::create('payment_callbacks', function (Blueprint $table) {
            $table->comment('支付回调流水表');
            $table->id();
            $table->unsignedInteger('payment_id')->nullable()->comment('关联支付单ID,未匹配到支付单时为空');
            $table->string('channel', 20)->comment('渠道:wechat,alipay');
            $table->string('event_type', 30)->comment('事件类型:payment=支付通知,refund=退款通知');
            $table->string('notify_id', 64)->nullable()->comment('第三方通知唯一ID,幂等去重依据');
            $table->string('trade_no', 64)->nullable()->comment('第三方交易号');
            $table->text('raw_content')->comment('原始报文(JSON)');
            $table->tinyInteger('sign_verified')->default(0)->comment('验签结果:0=未通过,1=通过');
            $table->tinyInteger('status')->default(0)->comment('处理状态:0=未处理,1=已处理,2=异常忽略');
            $table->timestamp('processed_at')->nullable()->comment('处理时间');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->unique(['channel', 'notify_id'], 'uk_payment_callbacks_channel_notify');
            $table->index('payment_id', 'idx_payment_callbacks_payment_id');
            $table->index('trade_no', 'idx_payment_callbacks_trade_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_callbacks');
        Schema::dropIfExists('payment_channels');
        Schema::dropIfExists('payments');
    }
};
