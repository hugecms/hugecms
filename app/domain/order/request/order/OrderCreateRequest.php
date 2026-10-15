<?php

declare(strict_types=1);

namespace app\domain\order\request\order;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderCreateRequest',
    required: [
        'id',
        'order_no',
        'user_id',
        'total_amount',
        'freight_amount',
        'discount_amount',
        'pay_amount',
        'status',
        'receiver_name',
        'receiver_phone',
        'receiver_region',
        'receiver_address',
        'buyer_remark',
        'pay_time',
        'deliver_time',
        'finish_time',
        'cancel_time',
        'cancel_reason',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'order_no', description: '订单号', type: 'string'),
        new OA\Property(property: 'user_id', description: '下单用户ID', type: 'integer'),
        new OA\Property(property: 'total_amount', description: '商品总额', type: 'float'),
        new OA\Property(property: 'freight_amount', description: '运费', type: 'float'),
        new OA\Property(property: 'discount_amount', description: '优惠金额', type: 'float'),
        new OA\Property(property: 'pay_amount', description: '实付金额', type: 'float'),
        new OA\Property(property: 'status', description: '状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消', type: 'integer'),
        new OA\Property(property: 'receiver_name', description: '收货人', type: 'string'),
        new OA\Property(property: 'receiver_phone', description: '收货电话', type: 'string'),
        new OA\Property(property: 'receiver_region', description: '省市区', type: 'string'),
        new OA\Property(property: 'receiver_address', description: '详细地址', type: 'string'),
        new OA\Property(property: 'buyer_remark', description: '买家备注', type: 'string'),
        new OA\Property(property: 'pay_time', description: '支付时间', type: 'string'),
        new OA\Property(property: 'deliver_time', description: '发货时间', type: 'string'),
        new OA\Property(property: 'finish_time', description: '完成时间', type: 'string'),
        new OA\Property(property: 'cancel_time', description: '取消时间', type: 'string'),
        new OA\Property(property: 'cancel_reason', description: '取消原因', type: 'string'),
    ]
)]
class OrderCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'order_no' => 'require',
        'user_id' => 'require',
        'total_amount' => 'require',
        'freight_amount' => 'require',
        'discount_amount' => 'require',
        'pay_amount' => 'require',
        'status' => 'require',
        'receiver_name' => 'require',
        'receiver_phone' => 'require',
        'receiver_region' => 'require',
        'receiver_address' => 'require',
        'buyer_remark' => 'require',
        'pay_time' => 'require',
        'deliver_time' => 'require',
        'finish_time' => 'require',
        'cancel_time' => 'require',
        'cancel_reason' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'order_no.require' => '请设置订单号',
        'user_id.require' => '请设置下单用户ID',
        'total_amount.require' => '请设置商品总额',
        'freight_amount.require' => '请设置运费',
        'discount_amount.require' => '请设置优惠金额',
        'pay_amount.require' => '请设置实付金额',
        'status.require' => '请设置状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消',
        'receiver_name.require' => '请设置收货人',
        'receiver_phone.require' => '请设置收货电话',
        'receiver_region.require' => '请设置省市区',
        'receiver_address.require' => '请设置详细地址',
        'buyer_remark.require' => '请设置买家备注',
        'pay_time.require' => '请设置支付时间',
        'deliver_time.require' => '请设置发货时间',
        'finish_time.require' => '请设置完成时间',
        'cancel_time.require' => '请设置取消时间',
        'cancel_reason.require' => '请设置取消原因',
    ];
}
