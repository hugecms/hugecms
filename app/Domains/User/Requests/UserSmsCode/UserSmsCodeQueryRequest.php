<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSmsCode;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSmsCodeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getScene, description: '场景:register,login,reset_password', type: 'string'),
        new OA\Property(property: self::getIp, description: '发送IP,用于防刷', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserSmsCodeQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPhone = 'phone';

    public const string getScene = 'scene';

    public const string getIp = 'ip';

    public const string getCreateTime = 'createTime';

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
