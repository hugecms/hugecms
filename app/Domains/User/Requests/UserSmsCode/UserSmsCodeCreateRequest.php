<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSmsCode;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSmsCodeCreateRequest',
    required: [
        self::getPhone,
        self::getCode,
        self::getScene,
        self::getExpireAt,
        self::getUsedAt,
        self::getIp,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getCode, description: '验证码', type: 'string'),
        new OA\Property(property: self::getScene, description: '场景:register,login,reset_password', type: 'string'),
        new OA\Property(property: self::getExpireAt, description: '过期时间', type: 'string'),
        new OA\Property(property: self::getUsedAt, description: '使用时间', type: 'string'),
        new OA\Property(property: self::getIp, description: '发送IP,用于防刷', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class UserSmsCodeCreateRequest extends FormRequest
{
    public const string getPhone = 'phone';

    public const string getCode = 'code';

    public const string getScene = 'scene';

    public const string getExpireAt = 'expireAt';

    public const string getUsedAt = 'usedAt';

    public const string getIp = 'ip';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getPhone => 'required',
            self::getCode => 'required',
            self::getScene => 'required',
            self::getExpireAt => 'required',
            self::getUsedAt => 'required',
            self::getIp => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPhone.'.required' => '请设置手机号',
            self::getCode.'.required' => '请设置验证码',
            self::getScene.'.required' => '请设置场景:register,login,reset_password',
            self::getExpireAt.'.required' => '请设置过期时间',
            self::getUsedAt.'.required' => '请设置使用时间',
            self::getIp.'.required' => '请设置发送IP,用于防刷',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
