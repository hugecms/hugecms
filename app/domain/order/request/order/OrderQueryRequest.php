<?php

declare(strict_types=1);

namespace app\domain\order\request\order;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
