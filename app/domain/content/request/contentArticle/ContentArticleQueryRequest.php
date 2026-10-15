<?php

declare(strict_types=1);

namespace app\domain\content\request\contentArticle;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentArticleQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class ContentArticleQueryRequest extends Validate
{
    protected $rule = [

    ];

    protected $message = [

    ];
}
