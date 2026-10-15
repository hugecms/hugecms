<?php

declare(strict_types=1);

namespace app\domain\user\request\userAddress;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserAddressUpdateRequest',
    required: [
        'id',
        'user_id',
        'receiver_name',
        'receiver_phone',
        'receiver_region',
        'receiver_address',
        'is_default',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'receiver_name', description: '收货人', type: 'string'),
        new OA\Property(property: 'receiver_phone', description: '收货电话', type: 'string'),
        new OA\Property(property: 'receiver_region', description: '省市区', type: 'string'),
        new OA\Property(property: 'receiver_address', description: '详细地址', type: 'string'),
        new OA\Property(property: 'is_default', description: '默认地址:0=否,1=是', type: 'integer'),
    ]
)]
class UserAddressUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'receiver_name' => 'require',
        'receiver_phone' => 'require',
        'receiver_region' => 'require',
        'receiver_address' => 'require',
        'is_default' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'receiver_name.require' => '请设置收货人',
        'receiver_phone.require' => '请设置收货电话',
        'receiver_region.require' => '请设置省市区',
        'receiver_address.require' => '请设置详细地址',
        'is_default.require' => '请设置默认地址:0=否,1=是',
    ];
}
