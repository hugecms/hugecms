<?php

declare(strict_types=1);

namespace app\domain\product\request\product;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ProductQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
