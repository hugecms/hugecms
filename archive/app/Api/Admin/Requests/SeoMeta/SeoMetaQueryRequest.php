<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\SeoMeta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SeoMetaQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTargetId, description: '对应的目标实体ID', type: 'integer'),
    ]
)]
class SeoMetaQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
