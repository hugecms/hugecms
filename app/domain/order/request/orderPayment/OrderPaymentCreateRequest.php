<?php

declare(strict_types=1);

namespace app\domain\order\request\orderPayment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderPaymentCreateRequest',
    required: [
        'id',
        'payment_no',
        'order_id',
        'pay_method',
        'amount',
        'status',
        'trade_no',
        'paid_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'payment_no', description: '支付单号', type: 'string'),
        new OA\Property(property: 'order_id', description: '订单ID', type: 'integer'),
        new OA\Property(property: 'pay_method', description: '支付方式:wechat,alipay', type: 'string'),
        new OA\Property(property: 'amount', description: '支付金额', type: 'float'),
        new OA\Property(property: 'status', description: '状态:0=待支付,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: 'trade_no', description: '第三方交易号', type: 'string'),
        new OA\Property(property: 'paid_at', description: '支付成功时间', type: 'string'),
    ]
)]
class OrderPaymentCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'payment_no' => 'require',
        'order_id' => 'require',
        'pay_method' => 'require',
        'amount' => 'require',
        'status' => 'require',
        'trade_no' => 'require',
        'paid_at' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'payment_no.require' => '请设置支付单号',
        'order_id.require' => '请设置订单ID',
        'pay_method.require' => '请设置支付方式:wechat,alipay',
        'amount.require' => '请设置支付金额',
        'status.require' => '请设置状态:0=待支付,1=成功,2=失败',
        'trade_no.require' => '请设置第三方交易号',
        'paid_at.require' => '请设置支付成功时间',
    ];
}
