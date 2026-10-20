<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentCommentLike;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCommentLikeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCommentId, description: '评论ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '点赞用户ID', type: 'integer'),
    ]
)]
class ContentCommentLikeQueryRequest extends FormRequest
{
    public const string getCommentId = 'commentId';

    public const string getUserId = 'userId';

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
