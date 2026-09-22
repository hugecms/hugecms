<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态', type: 'string'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getUserId, description: '评论者用户ID（NULL表示游客）', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父评论ID（0=顶级评论，支持楼中楼）', type: 'integer'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class CommentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getStatus = 'status';

    public const string getKeyword = 'keyword';

    public const string getUserId = 'userId';

    public const string getParentId = 'parentId';

    public const string getCreatedAt = 'createdAt';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
