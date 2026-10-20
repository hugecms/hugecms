<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTopic;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTopicQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSlug, description: '专题标识,用于URL', type: 'string'),
    ]
)]
class ContentTopicQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSlug = 'slug';

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
