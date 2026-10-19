<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateOrderTables extends Migrator
{
    /**
     * 订单域：购物车、订单、明细、支付单、退款单、发货单
     */
    public function change()
    {
        // 订单表：收货人信息为快照，不随地址簿变化
        $orders = $this->table('orders', ['comment' => '订单表']);
        $orders->addColumn(Column::string('order_no', 30)->setNull(false)->setComment('订单号'))
            ->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('下单用户ID'))
            ->addColumn(Column::decimal('total_amount', 10, 2)->setNull(false)->setComment('商品总额'))
            ->addColumn(Column::decimal('freight_amount', 10, 2)->setNull(false)->setDefault(0)->setComment('运费'))
            ->addColumn(Column::decimal('discount_amount', 10, 2)->setNull(false)->setDefault(0)->setComment('优惠金额'))
            ->addColumn(Column::decimal('pay_amount', 10, 2)->setNull(false)->setComment('实付金额'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消'))
            ->addColumn(Column::string('receiver_name', 50)->setNull(false)->setComment('收货人'))
            ->addColumn(Column::string('receiver_phone', 20)->setNull(false)->setComment('收货电话'))
            ->addColumn(Column::string('receiver_region', 100)->setNull(false)->setComment('省市区'))
            ->addColumn(Column::string('receiver_address', 255)->setNull(false)->setComment('详细地址'))
            ->addColumn(Column::string('buyer_remark', 255)->setNullable()->setComment('买家备注'))
            ->addColumn(Column::dateTime('pay_time')->setNullable()->setComment('支付时间'))
            ->addColumn(Column::dateTime('deliver_time')->setNullable()->setComment('发货时间'))
            ->addColumn(Column::dateTime('finish_time')->setNullable()->setComment('完成时间'))
            ->addColumn(Column::dateTime('cancel_time')->setNullable()->setComment('取消时间'))
            ->addColumn(Column::string('cancel_reason', 255)->setNullable()->setComment('取消原因'))
            ->addTimestamps()
            ->addIndex('order_no', ['unique' => true, 'name' => 'uk_orders_order_no'])
            ->addIndex(['user_id', 'status'], ['name' => 'idx_orders_user_status'])
            ->create();

        // 订单明细表：商品信息快照，防商品改价/改名影响历史订单
        $orderItems = $this->table('order_items', ['comment' => '订单明细表']);
        $orderItems->addColumn(Column::unsignedInteger('order_id')->setNull(false)->setComment('订单ID'))
            ->addColumn(Column::unsignedInteger('product_id')->setNull(false)->setComment('商品ID'))
            ->addColumn(Column::unsignedInteger('sku_id')->setNullable()->setComment('SKU ID'))
            ->addColumn(Column::string('product_name', 200)->setNull(false)->setComment('商品名称快照'))
            ->addColumn(Column::string('sku_specs', 500)->setNullable()->setComment('规格快照'))
            ->addColumn(Column::string('image', 255)->setNullable()->setComment('商品图快照'))
            ->addColumn(Column::decimal('price', 10, 2)->setNull(false)->setComment('成交单价'))
            ->addColumn(Column::unsignedInteger('quantity')->setNull(false)->setDefault(1)->setComment('数量'))
            ->addColumn(Column::decimal('total_amount', 10, 2)->setNull(false)->setComment('小计'))
            ->addTimestamps('create_time', null)
            ->addIndex('order_id', ['name' => 'idx_order_items_order_id'])
            ->addIndex('product_id', ['name' => 'idx_order_items_product_id'])
            ->create();

        // 购物车表：交易链路起点，结算时转入 order_items；游客购物车不落库，登录后合并
        $carts = $this->table('order_carts', ['comment' => '购物车表']);
        $carts->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('用户ID'))
            ->addColumn(Column::unsignedInteger('product_id')->setNull(false)->setComment('商品ID'))
            ->addColumn(Column::unsignedInteger('sku_id')->setNull(false)->setDefault(0)->setComment('SKU ID,0=单规格'))
            ->addColumn(Column::string('product_name', 200)->setNull(false)->setComment('商品名称快照'))
            ->addColumn(Column::string('image', 255)->setNullable()->setComment('商品图快照'))
            ->addColumn(Column::decimal('price', 10, 2)->setNull(false)->setComment('加购时价格快照'))
            ->addColumn(Column::unsignedInteger('quantity')->setNull(false)->setDefault(1)->setComment('数量'))
            ->addColumn(Column::tinyInteger('checked')->setNull(false)->setDefault(1)->setComment('结算勾选:0=否,1=是'))
            ->addTimestamps()
            ->addIndex(['user_id', 'product_id', 'sku_id'], ['unique' => true, 'name' => 'uk_order_carts_user_product'])
            ->addIndex('product_id', ['name' => 'idx_order_carts_product_id'])
            ->create();

        // 支付单表：与订单一对多，记录第三方交易号
        $payments = $this->table('order_payments', ['comment' => '支付单表']);
        $payments->addColumn(Column::string('payment_no', 30)->setNull(false)->setComment('支付单号'))
            ->addColumn(Column::unsignedInteger('order_id')->setNull(false)->setComment('订单ID'))
            ->addColumn(Column::string('pay_method', 20)->setNull(false)->setComment('支付方式:wechat,alipay'))
            ->addColumn(Column::decimal('amount', 10, 2)->setNull(false)->setComment('支付金额'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=待支付,1=成功,2=失败'))
            ->addColumn(Column::string('trade_no', 64)->setNullable()->setComment('第三方交易号'))
            ->addColumn(Column::dateTime('paid_at')->setNullable()->setComment('支付成功时间'))
            ->addTimestamps()
            ->addIndex('payment_no', ['unique' => true, 'name' => 'uk_payments_payment_no'])
            ->addIndex('order_id', ['name' => 'idx_payments_order_id'])
            ->addIndex('trade_no', ['name' => 'idx_payments_trade_no'])
            ->create();

        // 退款单表
        $refunds = $this->table('order_refunds', ['comment' => '退款单表']);
        $refunds->addColumn(Column::string('refund_no', 30)->setNull(false)->setComment('退款单号'))
            ->addColumn(Column::unsignedInteger('order_id')->setNull(false)->setComment('订单ID'))
            ->addColumn(Column::unsignedInteger('payment_id')->setNullable()->setComment('支付单ID'))
            ->addColumn(Column::decimal('amount', 10, 2)->setNull(false)->setComment('退款金额'))
            ->addColumn(Column::string('reason', 255)->setNullable()->setComment('退款原因'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=待审核,1=退款中,2=已退款,3=已拒绝'))
            ->addColumn(Column::dateTime('refunded_at')->setNullable()->setComment('退款完成时间'))
            ->addTimestamps()
            ->addIndex('refund_no', ['unique' => true, 'name' => 'uk_refunds_refund_no'])
            ->addIndex('order_id', ['name' => 'idx_refunds_order_id'])
            ->create();

        // 发货单表：记录快递信息，支持一单多裹多次发货
        $shipments = $this->table('order_shipments', ['comment' => '发货单表']);
        $shipments->addColumn(Column::unsignedInteger('order_id')->setNull(false)->setComment('订单ID'))
            ->addColumn(Column::string('express_company', 50)->setNull(false)->setComment('快递公司'))
            ->addColumn(Column::string('express_no', 50)->setNull(false)->setComment('运单号'))
            ->addColumn(Column::string('remark', 255)->setNullable()->setComment('发货备注'))
            ->addTimestamps('create_time', null)
            ->addIndex('order_id', ['name' => 'idx_order_shipments_order_id'])
            ->addIndex('express_no', ['name' => 'idx_order_shipments_express_no'])
            ->create();
    }
}
