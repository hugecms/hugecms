<?php

declare(strict_types=1);

namespace app\domain\content\request\contentArticleContent;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentArticleContentQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentArticleContentQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
