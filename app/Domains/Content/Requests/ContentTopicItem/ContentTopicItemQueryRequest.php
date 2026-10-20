<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTopicItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTopicItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTopicId, description: '专题ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
    ]
)]
class ContentTopicItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTopicId = 'topicId';

    public const string getTargetType = 'targetType';

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
