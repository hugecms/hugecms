<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentPage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentPageQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSlug, description: '页面标识,如:about', type: 'string'),
    ]
)]
class ContentPageQueryRequest extends FormRequest
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
