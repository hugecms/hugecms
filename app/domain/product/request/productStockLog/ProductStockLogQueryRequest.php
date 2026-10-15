<?php

declare(strict_types=1);

namespace app\domain\product\request\productStockLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductStockLogQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ProductStockLogQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
