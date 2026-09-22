<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RolePermissionUpdateRequest',
    required: [
        self::getId,
        self::getRoleId,
        self::getPermissionId,
        self::getIsDenied,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getPermissionId, description: '权限ID', type: 'integer'),
        new OA\Property(property: self::getIsDenied, description: '0=允许，1=拒绝（拒绝优先，覆盖性授权）', type: 'integer'),
    ]
)]
class RolePermissionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRoleId = 'roleId';

    public const string getPermissionId = 'permissionId';

    public const string getIsDenied = 'isDenied';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRoleId => 'required',
            self::getPermissionId => 'required',
            self::getIsDenied => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRoleId.'.required' => '请设置角色ID',
            self::getPermissionId.'.required' => '请设置权限ID',
            self::getIsDenied.'.required' => '请设置0=允许，1=拒绝（拒绝优先，覆盖性授权）',
        ];
    }
}
