<?php

declare(strict_types=1);

namespace app\domain\content\request\contentTag;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentTagUpdateRequest',
    required: [
        'id',
        'name',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '标签名', type: 'string'),
    ]
)]
class ContentTagUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置标签名',
    ];
}
