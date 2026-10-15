<?php

declare(strict_types=1);

namespace app\domain\product\request\productBrand;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductBrandQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ProductBrandQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
