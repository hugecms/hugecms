<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminUserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminUserRoleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
    ]
)]
class AdminUserRoleQueryRequest extends FormRequest
{
    public const string getRoleId = 'roleId';

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
