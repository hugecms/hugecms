<?php

declare(strict_types=1);

namespace App\Domains\Auth\Requests\AuthRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthRoleCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class AuthRoleCreateRequest extends FormRequest
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
