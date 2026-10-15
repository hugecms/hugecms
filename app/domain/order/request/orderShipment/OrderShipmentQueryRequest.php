<?php

declare(strict_types=1);

namespace app\domain\order\request\orderShipment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderShipmentQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class OrderShipmentQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
