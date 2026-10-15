<?php

declare(strict_types=1);

namespace app\domain\order\request\orderShipment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderShipmentCreateRequest',
    required: [
        'id',
        'order_id',
        'express_company',
        'express_no',
        'remark',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'order_id', description: '订单ID', type: 'integer'),
        new OA\Property(property: 'express_company', description: '快递公司', type: 'string'),
        new OA\Property(property: 'express_no', description: '运单号', type: 'string'),
        new OA\Property(property: 'remark', description: '发货备注', type: 'string'),
    ]
)]
class OrderShipmentCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'order_id' => 'require',
        'express_company' => 'require',
        'express_no' => 'require',
        'remark' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'order_id.require' => '请设置订单ID',
        'express_company.require' => '请设置快递公司',
        'express_no.require' => '请设置运单号',
        'remark.require' => '请设置发货备注',
    ];
}
