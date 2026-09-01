<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class ContentCreateRequest extends FormRequest
{
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
