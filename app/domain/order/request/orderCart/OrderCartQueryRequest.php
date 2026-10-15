<?php

declare(strict_types=1);

namespace app\domain\order\request\orderCart;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderCartQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderCartQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
