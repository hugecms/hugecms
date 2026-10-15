<?php

declare(strict_types=1);

namespace app\domain\admin\request\adminUser;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'AdminUserQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class AdminUserQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
