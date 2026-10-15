<?php

declare(strict_types=1);

namespace app\domain\content\request\contentCategory;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentCategoryQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentCategoryQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
