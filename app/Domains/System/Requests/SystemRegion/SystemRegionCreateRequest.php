<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRegionCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class SystemRegionCreateRequest extends FormRequest
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
