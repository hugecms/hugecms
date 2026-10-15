<?php

declare(strict_types=1);

namespace app\domain\system\request\systemDictItem;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemDictItemQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemDictItemQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
