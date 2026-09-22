<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentRevision;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentRevisionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '内容主表ID', type: 'integer'),
    ]
)]
class ContentRevisionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

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
