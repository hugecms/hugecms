<?php

declare(strict_types=1);

namespace app\domain\order\request\orderPayment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderPaymentQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderPaymentQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
