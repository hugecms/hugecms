<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemLoginLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemLoginLogCreateRequest',
    required: [
        self::getUserType,
        self::getUserId,
        self::getUsername,
        self::getStatus,
        self::getIp,
        self::getUserAgent,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserType, description: '账户类型:admin=后台,user=前台', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID,账号不存在时为空', type: 'integer'),
        new OA\Property(property: self::getUsername, description: '登录账号(尝试值快照)', type: 'string'),
        new OA\Property(property: self::getStatus, description: '结果:0=失败,1=成功', type: 'integer'),
        new OA\Property(property: self::getIp, description: '登录IP', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: 'User-Agent', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemLoginLogCreateRequest extends FormRequest
{
    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public const string getUsername = 'username';

    public const string getStatus = 'status';

    public const string getIp = 'ip';

    public const string getUserAgent = 'userAgent';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserType => 'required',
            self::getUserId => 'required',
            self::getUsername => 'required',
            self::getStatus => 'required',
            self::getIp => 'required',
            self::getUserAgent => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserType.'.required' => '请设置账户类型:admin=后台,user=前台',
            self::getUserId.'.required' => '请设置用户ID,账号不存在时为空',
            self::getUsername.'.required' => '请设置登录账号(尝试值快照)',
            self::getStatus.'.required' => '请设置结果:0=失败,1=成功',
            self::getIp.'.required' => '请设置登录IP',
            self::getUserAgent.'.required' => '请设置User-Agent',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
