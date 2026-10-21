<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 订单域：购物车、订单、明细、优惠明细、订单日志、退款单（含审核备注/凭证图/协商日志）、发货单及明细、发票
     * 支付单独立为支付域（payments 表），通过 biz_type/biz_id 关联
     */
    public function up(): void
    {
        // 订单表：收货人信息为快照，不随地址簿变化
        Schema::create('orders', function (Blueprint $table) {
            $table->comment('订单表');
            $table->id();
            $table->string('order_no', 30)->comment('订单号');
            $table->string('request_token', 64)->nullable()->comment('下单请求幂等令牌,客户端生成,唯一约束防重复下单');
            $table->string('order_channel', 20)->default('')->comment('下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用');
            $table->unsignedInteger('user_id')->comment('下单用户ID');
            $table->decimal('total_amount', 10, 2)->comment('商品总额');
            $table->decimal('freight_amount', 10, 2)->default(0)->comment('运费');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('优惠金额');
            $table->decimal('pay_amount', 10, 2)->comment('实付金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)');
            $table->tinyInteger('refund_status')->default(0)->comment('售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写');
            $table->string('receiver_name', 50)->comment('收货人');
            $table->string('receiver_phone', 20)->comment('收货电话');
            $table->string('receiver_region', 100)->comment('省市区(合并展示用)');
            $table->string('receiver_province', 50)->nullable()->comment('省');
            $table->string('receiver_city', 50)->nullable()->comment('市');
            $table->string('receiver_district', 50)->nullable()->comment('区县');
            $table->string('receiver_province_code', 20)->nullable()->comment('省行政区划码,物流面单');
            $table->string('receiver_city_code', 20)->nullable()->comment('市行政区划码');
            $table->string('receiver_district_code', 20)->nullable()->comment('区县行政区划码');
            $table->string('receiver_address', 255)->comment('详细地址');
            $table->string('buyer_remark', 255)->nullable()->comment('买家备注');
            $table->dateTime('pay_time')->nullable()->comment('支付时间');
            $table->dateTime('deliver_time')->nullable()->comment('发货时间');
            $table->dateTime('finish_time')->nullable()->comment('完成时间');
            $table->dateTime('cancel_time')->nullable()->comment('取消时间');
            $table->string('cancel_reason', 255)->nullable()->comment('取消原因');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('order_no', 'uk_orders_order_no');
            $table->unique('request_token', 'uk_orders_request_token');
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            $table->index(['order_channel', 'created_at'], 'idx_orders_channel_time');
        });

        // 订单明细表：商品信息快照，防商品改价/改名影响历史订单
        Schema::create('order_items', function (Blueprint $table) {
            $table->comment('订单明细表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格');
            $table->string('product_name', 200)->comment('商品名称快照');
            $table->string('sku_specs', 500)->nullable()->comment('规格快照');
            $table->string('sku_no', 64)->nullable()->comment('SKU编号快照');
            $table->string('sku_name', 200)->nullable()->comment('SKU名称快照');
            $table->string('sku_image', 255)->nullable()->comment('SKU图片快照');
            $table->string('image', 255)->nullable()->comment('商品图快照');
            $table->decimal('price', 10, 2)->comment('成交单价');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->decimal('total_amount', 10, 2)->comment('小计');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('order_id', 'idx_order_items_order_id');
            $table->index('product_id', 'idx_order_items_product_id');
        });

        // 订单优惠明细表：拆分 discount_amount 构成（券/积分/促销），部分退款摊销与营销费用对账依据
        Schema::create('order_promotions', function (Blueprint $table) {
            $table->comment('订单优惠明细表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->string('type', 20)->comment('优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动');
            $table->unsignedInteger('related_id')->nullable()->comment('关联ID:coupon=用户券ID,promotion=活动ID,points=空');
            $table->string('name', 100)->comment('优惠名称快照,如:满100减20');
            $table->unsignedInteger('order_item_id')->nullable()->comment('订单明细ID,空=全单优惠');
            $table->decimal('amount', 10, 2)->comment('抵扣金额');
            $table->unsignedInteger('points')->default(0)->comment('消耗积分,积分抵扣时记录,退款按比例返还');
            $table->timestamps();
            $table->index('order_id', 'idx_order_promotions_order_id');
            $table->index(['type', 'related_id'], 'idx_order_promotions_type_related');
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

        // 退款单表
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->comment('退款单表');
            $table->id();
            $table->string('refund_no', 30)->comment('退款单号');
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('payment_id')->nullable()->comment('关联支付单ID(payments 表)');
            $table->tinyInteger('type')->default(1)->comment('售后类型:1=仅退款,2=退货退款');
            $table->decimal('amount', 10, 2)->comment('退款金额');
            $table->string('reason', 255)->nullable()->comment('退款原因');
            $table->string('admin_note', 255)->nullable()->comment('商家审核备注/拒绝原因');
            $table->json('images')->nullable()->comment('申请凭证图片(JSON数组)');
            $table->string('return_express_company', 50)->nullable()->comment('退货物流公司,退货退款时填写');
            $table->string('return_express_no', 50)->nullable()->comment('退货物流单号');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待审核,1=退款中,2=已退款,3=已拒绝');
            $table->dateTime('refunded_at')->nullable()->comment('退款完成时间');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('refund_no', 'uk_order_refunds_refund_no');
            $table->index('order_id', 'idx_order_refunds_order_id');
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

        // 订单操作日志表：状态每次流转留痕，追溯"谁在何时做了什么"
        Schema::create('order_logs', function (Blueprint $table) {
            $table->comment('订单操作日志表');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->tinyInteger('from_status')->nullable()->comment('变更前状态');
            $table->tinyInteger('to_status')->comment('变更后状态');
            $table->string('operator_type', 20)->default('user')->comment('操作账户类型:admin=后台,user=前台,system=系统');
            $table->unsignedInteger('operator_id')->nullable()->comment('操作人ID');
            $table->string('operator', 50)->comment('操作人名称快照');
            $table->string('note', 255)->nullable()->comment('操作备注');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('order_id', 'idx_order_logs_order_id');
        });

        // 发货单明细表：支持一单多裹，按订单明细拆分发货数量
        Schema::create('order_shipment_items', function (Blueprint $table) {
            $table->comment('发货单明细表');
            $table->id();
            $table->unsignedInteger('shipment_id')->comment('发货单ID');
            $table->unsignedInteger('order_item_id')->comment('订单明细ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格');
            $table->unsignedInteger('quantity')->comment('发货数量');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('shipment_id', 'idx_order_shipment_items_shipment_id');
            $table->index('order_item_id', 'idx_order_shipment_items_order_item_id');
        });

        // 退货明细表：退款单按明细部分退货，金额与数量快照
        Schema::create('order_refund_items', function (Blueprint $table) {
            $table->comment('退货明细表');
            $table->id();
            $table->unsignedInteger('refund_id')->comment('退款单ID');
            $table->unsignedInteger('order_item_id')->comment('订单明细ID');
            $table->unsignedInteger('product_id')->comment('商品ID');
            $table->unsignedInteger('sku_id')->default(0)->comment('SKU ID,0=单规格');
            $table->unsignedInteger('quantity')->comment('退货数量');
            $table->decimal('amount', 10, 2)->comment('退款金额');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('refund_id', 'idx_order_refund_items_refund_id');
            $table->index('order_item_id', 'idx_order_refund_items_order_item_id');
        });

        // 售后协商日志表：申请/协商/审核/退货/退款全流程留痕，风格同 order_logs
        Schema::create('order_refund_logs', function (Blueprint $table) {
            $table->comment('售后协商日志表');
            $table->id();
            $table->unsignedInteger('refund_id')->comment('退款单ID');
            $table->string('operator_type', 20)->default('user')->comment('操作账户类型:admin=后台,user=前台,system=系统');
            $table->unsignedInteger('operator_id')->nullable()->comment('操作人ID');
            $table->string('operator', 50)->comment('操作人名称快照');
            $table->string('content', 500)->comment('协商内容/操作说明');
            $table->json('images')->nullable()->comment('凭证图片(JSON数组)');
            $table->timestamp('create_time')->useCurrent()->comment('创建时间');
            $table->index('refund_id', 'idx_order_refund_logs_refund_id');
        });

        // 订单发票表：开票申请与开具记录
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->comment('订单发票表(合规必需:用户开票申请与发票状态跟踪)');
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->tinyInteger('type')->default(1)->comment('类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票');
            $table->string('title', 200)->comment('发票抬头');
            $table->string('tax_no', 30)->nullable()->comment('纳税人识别号,个人抬头为空');
            $table->decimal('amount', 10, 2)->comment('开票金额');
            $table->tinyInteger('status')->default(0)->comment('状态:0=待开票,1=已开票,2=已作废');
            $table->string('invoice_no', 50)->nullable()->comment('发票号码');
            $table->dateTime('invoiced_at')->nullable()->comment('开票时间');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->timestamps();
            $table->index('order_id', 'idx_order_invoices_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_refund_logs');
        Schema::dropIfExists('order_invoices');
        Schema::dropIfExists('order_refund_items');
        Schema::dropIfExists('order_shipment_items');
        Schema::dropIfExists('order_logs');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_refunds');
        Schema::dropIfExists('order_carts');
        Schema::dropIfExists('order_promotions');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
