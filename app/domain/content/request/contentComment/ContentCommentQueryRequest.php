<?php

declare(strict_types=1);

namespace app\domain\content\request\contentComment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentCommentQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentCommentQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
