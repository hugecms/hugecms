<?php

declare(strict_types=1);

namespace app\domain\user\request\userFavorite;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserFavoriteUpdateRequest',
    required: [
        'id',
        'user_id',
        'target_type',
        'target_id',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'target_type', description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: 'target_id', description: '目标ID', type: 'integer'),
    ]
)]
class UserFavoriteUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'target_type' => 'require',
        'target_id' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'target_type.require' => '请设置目标类型:article,product',
        'target_id.require' => '请设置目标ID',
    ];
}
