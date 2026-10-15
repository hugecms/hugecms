<?php

declare(strict_types=1);

namespace app\domain\system\request\systemOperationLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemOperationLogQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemOperationLogQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
