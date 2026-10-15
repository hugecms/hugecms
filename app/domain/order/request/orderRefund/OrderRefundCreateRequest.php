<?php

declare(strict_types=1);

namespace app\domain\order\request\orderRefund;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderRefundCreateRequest',
    required: [
        'id',
        'refund_no',
        'order_id',
        'payment_id',
        'amount',
        'reason',
        'status',
        'refunded_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'refund_no', description: '退款单号', type: 'string'),
        new OA\Property(property: 'order_id', description: '订单ID', type: 'integer'),
        new OA\Property(property: 'payment_id', description: '支付单ID', type: 'integer'),
        new OA\Property(property: 'amount', description: '退款金额', type: 'float'),
        new OA\Property(property: 'reason', description: '退款原因', type: 'string'),
        new OA\Property(property: 'status', description: '状态:0=待审核,1=退款中,2=已退款,3=已拒绝', type: 'integer'),
        new OA\Property(property: 'refunded_at', description: '退款完成时间', type: 'string'),
    ]
)]
class OrderRefundCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'refund_no' => 'require',
        'order_id' => 'require',
        'payment_id' => 'require',
        'amount' => 'require',
        'reason' => 'require',
        'status' => 'require',
        'refunded_at' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'refund_no.require' => '请设置退款单号',
        'order_id.require' => '请设置订单ID',
        'payment_id.require' => '请设置支付单ID',
        'amount.require' => '请设置退款金额',
        'reason.require' => '请设置退款原因',
        'status.require' => '请设置状态:0=待审核,1=退款中,2=已退款,3=已拒绝',
        'refunded_at.require' => '请设置退款完成时间',
    ];
}
