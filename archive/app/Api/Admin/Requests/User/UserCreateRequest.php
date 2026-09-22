<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCreateRequest',
    required: [
        self::getName,
        self::getEmail,
        self::getEmailVerifiedAt,
        self::getPassword,
        self::getAvatar,
        self::getStatus,
        self::getLastLoginIp,
        self::getLastLoginTime,
        self::getRememberToken,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '显示昵称', type: 'string'),
        new OA\Property(property: self::getEmail, description: '', type: 'string'),
        new OA\Property(property: self::getEmailVerifiedAt, description: '', type: 'string'),
        new OA\Property(property: self::getPassword, description: '', type: 'string'),
        new OA\Property(property: self::getAvatar, description: '头像URL', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0禁用，1启用', type: 'integer'),
        new OA\Property(property: self::getLastLoginIp, description: '最后登录IP（支持IPv6）', type: 'string'),
        new OA\Property(property: self::getLastLoginTime, description: '最后登录时间', type: 'string'),
        new OA\Property(property: self::getRememberToken, description: '', type: 'string'),
    ]
)]
class UserCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getEmail = 'email';

    public const string getEmailVerifiedAt = 'emailVerifiedAt';

    public const string getPassword = 'password';

    public const string getAvatar = 'avatar';

    public const string getStatus = 'status';

    public const string getLastLoginIp = 'lastLoginIp';

    public const string getLastLoginTime = 'lastLoginTime';

    public const string getRememberToken = 'rememberToken';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getEmail => 'required',
            self::getEmailVerifiedAt => 'required',
            self::getPassword => 'required',
            self::getAvatar => 'required',
            self::getStatus => 'required',
            self::getLastLoginIp => 'required',
            self::getLastLoginTime => 'required',
            self::getRememberToken => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置显示昵称',
            self::getEmail.'.required' => '请设置',
            self::getEmailVerifiedAt.'.required' => '请设置',
            self::getPassword.'.required' => '请设置',
            self::getAvatar.'.required' => '请设置头像URL',
            self::getStatus.'.required' => '请设置状态：0禁用，1启用',
            self::getLastLoginIp.'.required' => '请设置最后登录IP（支持IPv6）',
            self::getLastLoginTime.'.required' => '请设置最后登录时间',
            self::getRememberToken.'.required' => '请设置',
        ];
    }
}
