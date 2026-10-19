<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 订单域：购物车、订单、明细、支付单、退款单、发货单
     */
    public function up(): void
    {
        // 订单表：收货人信息为快照，不随地址簿变化
        Schema::create('orders', function (Blueprint $table) {
            $table->comment('订单表');
            $table->id();
            $table->string('order_no', 30)->comment('订单号');
            $table->unsignedInteger('user_id')->comment('下单用户ID');
            $table->decimal('total_amount', 10, 2)->comment('商品总额');
            $table->decimal('freight_amount', 10, 2)->default(0)->comment('运费');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('优惠金额');
            $table->decimal('pay_amount', 10, 2)->comment('实付金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消');
            $table->string('receiver_name', 50)->comment('收货人');
            $table->string('receiver_phone', 20)->comment('收货电话');
            $table->string('receiver_region', 100)->comment('省市区');
            $table->string('receiver_address', 255)->comment('详细地址');
            $table->string('buyer_remark', 255)->nullable()->comment('买家备注');
            $table->dateTime('pay_time')->nullable()->comment('支付时间');
            $table->dateTime('deliver_time')->nullable()->comment('发货时间');
            $table->dateTime('finish_time')->nullable()->comment('完成时间');
            $table->dateTime('cancel_time')->nullable()->comment('取消时间');
            $table->string('cancel_reason', 255)->nullable()->comment('取消原因');
            $table->timestamps();
            $table->unique('order_no', 'uk_orders_order_no');
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
        });

        // 订单明细表：商品信息快照，防商品改价/改名影响历史订单
        Schema::create('order_items', function (Blueprint $table) {
            $table->comment('订单明细表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->nullable()->comment('SKU ID');
            $table->string('product_name', 200)->comment('商品名称快照');
            $table->string('sku_specs', 500)->nullable()->comment('规格快照');
            $table->string('image', 255)->nullable()->comment('商品图快照');
            $table->decimal('price', 10, 2)->comment('成交单价');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->decimal('total_amount', 10, 2)->comment('小计');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('order_id', 'idx_order_items_order_id');
            $table->index('product_id', 'idx_order_items_product_id');
        });

        // 购物车表：交易链路起点，结算时转入 order_items；游客购物车不落库，登录后合并
        Schema::create('order_carts', function (Blueprint $table) {
            $table->comment('购物车表');
            $table->id();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格');
            $table->string('product_name', 200)->comment('商品名称快照');
            $table->string('image', 255)->nullable()->comment('商品图快照');
            $table->decimal('price', 10, 2)->comment('加购时价格快照');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->tinyInteger('checked')->default(1)->comment('结算勾选:0=否,1=是');
            $table->timestamps();
            $table->unique(['user_id', 'product_id', 'sku_id'], 'uk_order_carts_user_product');
            $table->index('product_id', 'idx_order_carts_product_id');
        });

        // 支付单表：与订单一对多，记录第三方交易号
        Schema::create('order_payments', function (Blueprint $table) {
            $table->comment('支付单表');
            $table->id();
            $table->string('payment_no', 30)->comment('支付单号');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->string('pay_method', 20)->comment('支付方式:wechat,alipay');
            $table->decimal('amount', 10, 2)->comment('支付金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待支付,1=成功,2=失败');
            $table->string('trade_no', 64)->nullable()->comment('第三方交易号');
            $table->dateTime('paid_at')->nullable()->comment('支付成功时间');
            $table->timestamps();
            $table->unique('payment_no', 'uk_payments_payment_no');
            $table->index('order_id', 'idx_payments_order_id');
            $table->index('trade_no', 'idx_payments_trade_no');
        });

        // 退款单表
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->comment('退款单表');
            $table->id();
            $table->string('refund_no', 30)->comment('退款单号');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('payment_id')->nullable()->comment('支付单ID');
            $table->decimal('amount', 10, 2)->comment('退款金额');
            $table->string('reason', 255)->nullable()->comment('退款原因');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审核,1=退款中,2=已退款,3=已拒绝');
            $table->dateTime('refunded_at')->nullable()->comment('退款完成时间');
            $table->timestamps();
            $table->unique('refund_no', 'uk_refunds_refund_no');
            $table->index('order_id', 'idx_refunds_order_id');
        });

        // 发货单表：记录快递信息，支持一单多裹多次发货
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->comment('发货单表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->string('express_company', 50)->comment('快递公司');
            $table->string('express_no', 50)->comment('运单号');
            $table->string('remark', 255)->nullable()->comment('发货备注');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('order_id', 'idx_order_shipments_order_id');
            $table->index('express_no', 'idx_order_shipments_express_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_refunds');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_carts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
