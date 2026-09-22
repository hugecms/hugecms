<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRoleCreateRequest',
    required: [
        self::getUserId,
        self::getRoleId,
        self::getDataScope,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
        new OA\Property(property: self::getDataScope, description: '数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）', type: 'string'),
    ]
)]
class UserRoleCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getRoleId = 'roleId';

    public const string getDataScope = 'dataScope';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getRoleId => 'required',
            self::getDataScope => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getRoleId.'.required' => '请设置角色ID',
            self::getDataScope.'.required' => '请设置数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）',
        ];
    }
}
