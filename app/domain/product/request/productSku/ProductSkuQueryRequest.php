<?php

declare(strict_types=1);

namespace app\domain\product\request\productSku;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductSkuQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ProductSkuQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
