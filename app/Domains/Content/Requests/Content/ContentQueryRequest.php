<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
    ]
)]
class ContentQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
