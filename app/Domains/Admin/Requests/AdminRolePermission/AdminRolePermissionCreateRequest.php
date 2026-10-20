<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminRolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRolePermissionCreateRequest',
    required: [
        self::getRoleId,
        self::getPermissionId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getPermissionId, description: '权限ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class AdminRolePermissionCreateRequest extends FormRequest
{
    public const string getRoleId = 'roleId';

    public const string getPermissionId = 'permissionId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getRoleId => 'required',
            self::getPermissionId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRoleId.'.required' => '请设置角色ID',
            self::getPermissionId.'.required' => '请设置权限ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
