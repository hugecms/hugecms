<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTagQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '标签名', type: 'string'),
    ]
)]
class ContentTagQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

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
