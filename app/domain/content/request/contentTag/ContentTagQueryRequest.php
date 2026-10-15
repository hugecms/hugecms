<?php

declare(strict_types=1);

namespace app\domain\content\request\contentTag;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentTagQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentTagQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
