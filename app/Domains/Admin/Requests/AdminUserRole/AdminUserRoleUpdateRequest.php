<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminUserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminUserRoleUpdateRequest',
    required: [
        self::getAdminUserId,
        self::getRoleId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getAdminUserId, description: '管理员ID', type: 'integer'),
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class AdminUserRoleUpdateRequest extends FormRequest
{
    public const string getAdminUserId = 'adminUserId';

    public const string getRoleId = 'roleId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getAdminUserId => 'required',
            self::getRoleId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAdminUserId.'.required' => '请设置管理员ID',
            self::getRoleId.'.required' => '请设置角色ID',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
