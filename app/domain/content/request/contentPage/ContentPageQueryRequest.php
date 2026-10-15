<?php

declare(strict_types=1);

namespace app\domain\content\request\contentPage;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentPageQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentPageQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
