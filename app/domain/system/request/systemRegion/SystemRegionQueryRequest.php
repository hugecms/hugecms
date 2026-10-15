<?php

declare(strict_types=1);

namespace app\domain\system\request\systemRegion;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemRegionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class SystemRegionQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
