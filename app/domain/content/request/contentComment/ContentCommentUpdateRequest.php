<?php

declare(strict_types=1);

namespace app\domain\content\request\contentComment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentCommentUpdateRequest',
    required: [
        'id',
        'user_id',
        'target_type',
        'target_id',
        'parent_id',
        'content',
        'status',
        'ip',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '评论用户ID', type: 'integer'),
        new OA\Property(property: 'target_type', description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: 'target_id', description: '目标ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父评论ID,0=顶级', type: 'integer'),
        new OA\Property(property: 'content', description: '评论内容', type: 'string'),
        new OA\Property(property: 'status', description: '状态:0=待审,1=通过,2=拒绝', type: 'integer'),
        new OA\Property(property: 'ip', description: '评论IP', type: 'string'),
    ]
)]
class ContentCommentUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'target_type' => 'require',
        'target_id' => 'require',
        'parent_id' => 'require',
        'content' => 'require',
        'status' => 'require',
        'ip' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置评论用户ID',
        'target_type.require' => '请设置目标类型:article,product',
        'target_id.require' => '请设置目标ID',
        'parent_id.require' => '请设置父评论ID,0=顶级',
        'content.require' => '请设置评论内容',
        'status.require' => '请设置状态:0=待审,1=通过,2=拒绝',
        'ip.require' => '请设置评论IP',
    ];
}
