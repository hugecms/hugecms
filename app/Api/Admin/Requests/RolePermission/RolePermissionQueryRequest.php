<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RolePermissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPermissionId, description: '权限ID', type: 'integer'),
    ]
)]
class RolePermissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
