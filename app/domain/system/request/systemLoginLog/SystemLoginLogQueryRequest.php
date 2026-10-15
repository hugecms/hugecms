<?php

declare(strict_types=1);

namespace app\domain\system\request\systemLoginLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemLoginLogQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemLoginLogQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
