<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminRolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRolePermissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getPermissionId, description: '权限ID', type: 'integer'),
    ]
)]
class AdminRolePermissionQueryRequest extends FormRequest
{
    public const string getRoleId = 'roleId';

    public const string getPermissionId = 'permissionId';

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
