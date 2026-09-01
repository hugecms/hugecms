<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSetting;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSettingCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class SystemSettingCreateRequest extends FormRequest
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
