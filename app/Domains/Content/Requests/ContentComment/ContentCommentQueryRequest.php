<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentComment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCommentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '评论用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
    ]
)]
class ContentCommentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getTargetId = 'targetId';

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
