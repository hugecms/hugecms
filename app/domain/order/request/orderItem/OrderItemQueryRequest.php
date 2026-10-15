<?php

declare(strict_types=1);

namespace app\domain\order\request\orderItem;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderItemQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderItemQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
