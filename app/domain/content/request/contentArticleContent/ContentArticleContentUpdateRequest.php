<?php

declare(strict_types=1);

namespace app\domain\content\request\contentArticleContent;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentArticleContentUpdateRequest',
    required: [
        'article_id',
        'content',
    ],
    properties: [
        new OA\Property(property: 'article_id', description: '文章ID', type: 'integer'),
        new OA\Property(property: 'content', description: '正文(富文本)', type: 'string'),
    ]
)]
class ContentArticleContentUpdateRequest extends Validate
{
    protected $rule = [
        'article_id' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'article_id.require' => '请设置文章ID',
        'content.require' => '请设置正文(富文本)',
    ];
}
