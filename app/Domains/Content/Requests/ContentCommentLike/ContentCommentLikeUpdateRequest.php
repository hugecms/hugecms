<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentCommentLike;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCommentLikeUpdateRequest',
    required: [
        self::getCommentId,
        self::getUserId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getCommentId, description: '评论ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '点赞用户ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class ContentCommentLikeUpdateRequest extends FormRequest
{
    public const string getCommentId = 'commentId';

    public const string getUserId = 'userId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getCommentId => 'required',
            self::getUserId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCommentId.'.required' => '请设置评论ID',
            self::getUserId.'.required' => '请设置点赞用户ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
