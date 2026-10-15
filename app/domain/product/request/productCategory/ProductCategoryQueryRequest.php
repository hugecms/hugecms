<?php

declare(strict_types=1);

namespace app\domain\product\request\productCategory;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductCategoryQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ProductCategoryQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
