<?php

declare(strict_types=1);

namespace app\domain\order\request\orderRefund;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderRefundQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderRefundQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
