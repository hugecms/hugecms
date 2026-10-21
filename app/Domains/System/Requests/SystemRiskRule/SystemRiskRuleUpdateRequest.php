<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRuleUpdateRequest',
    required: [
        self::getId,
        self::getIp,
        self::getReason,
        self::getExpiredAt,
        self::getUserType,
        self::getUserId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getIp, description: 'IP地址,支持IPv4/IPv6', type: 'string'),
        new OA\Property(property: self::getReason, description: '拉黑原因', type: 'string'),
        new OA\Property(property: self::getExpiredAt, description: '过期时间,空=永久', type: 'string'),
        new OA\Property(property: self::getUserType, description: '操作账户类型:admin,system', type: 'string'),
        new OA\Property(property: self::getUserId, description: '操作人ID', type: 'integer'),
    ]
)]
class SystemRiskRuleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getIp = 'ip';

    public const string getReason = 'reason';

    public const string getExpiredAt = 'expiredAt';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getIp => 'required',
            self::getReason => 'required',
            self::getExpiredAt => 'required',
            self::getUserType => 'required',
            self::getUserId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getIp.'.required' => '请设置IP地址,支持IPv4/IPv6',
            self::getReason.'.required' => '请设置拉黑原因',
            self::getExpiredAt.'.required' => '请设置过期时间,空=永久',
            self::getUserType.'.required' => '请设置操作账户类型:admin,system',
            self::getUserId.'.required' => '请设置操作人ID',
        ];
    }
}
