<?php

declare(strict_types=1);

namespace app\domain\admin\request\adminPermission;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'AdminPermissionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class AdminPermissionQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
