<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminUserUpdateRequest',
    required: [
        self::getId,
        self::getUsername,
        self::getEmail,
        self::getPhone,
        self::getPassword,
        self::getStatus,
        self::getIsSuper,
        self::getLastLoginTime,
        self::getLastLoginIp,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUsername, description: '用户名', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getPassword, description: '密码哈希', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=正常', type: 'integer'),
        new OA\Property(property: self::getIsSuper, description: '超级管理员:0=否,1=是;超管不受角色权限限制', type: 'integer'),
        new OA\Property(property: self::getLastLoginTime, description: '最后登录时间', type: 'string'),
        new OA\Property(property: self::getLastLoginIp, description: '最后登录IP', type: 'string'),
    ]
)]
class AdminUserUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUsername = 'username';

    public const string getEmail = 'email';

    public const string getPhone = 'phone';

    public const string getPassword = 'password';

    public const string getStatus = 'status';

    public const string getIsSuper = 'isSuper';

    public const string getLastLoginTime = 'lastLoginTime';

    public const string getLastLoginIp = 'lastLoginIp';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUsername => 'required',
            self::getEmail => 'required',
            self::getPhone => 'required',
            self::getPassword => 'required',
            self::getStatus => 'required',
            self::getIsSuper => 'required',
            self::getLastLoginTime => 'required',
            self::getLastLoginIp => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUsername.'.required' => '请设置用户名',
            self::getEmail.'.required' => '请设置邮箱',
            self::getPhone.'.required' => '请设置手机号',
            self::getPassword.'.required' => '请设置密码哈希',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=正常',
            self::getIsSuper.'.required' => '请设置超级管理员:0=否,1=是;超管不受角色权限限制',
            self::getLastLoginTime.'.required' => '请设置最后登录时间',
            self::getLastLoginIp.'.required' => '请设置最后登录IP',
        ];
    }
}
