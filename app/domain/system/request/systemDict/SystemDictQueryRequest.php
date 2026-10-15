<?php

declare(strict_types=1);

namespace app\domain\system\request\systemDict;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemDictQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemDictQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
