<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRecordCreateRequest',
    required: [
        self::getIp,
        self::getReason,
        self::getExpiredAt,
        self::getUserType,
        self::getUserId,
    ],
    properties: [
        new OA\Property(property: self::getIp, description: 'IP地址,支持IPv4/IPv6', type: 'string'),
        new OA\Property(property: self::getReason, description: '拉黑原因', type: 'string'),
        new OA\Property(property: self::getExpiredAt, description: '过期时间,空=永久', type: 'string'),
        new OA\Property(property: self::getUserType, description: '操作账户类型:admin,system', type: 'string'),
        new OA\Property(property: self::getUserId, description: '操作人ID', type: 'integer'),
    ]
)]
class SystemRiskRecordCreateRequest extends FormRequest
{
    public const string getIp = 'ip';

    public const string getReason = 'reason';

    public const string getExpiredAt = 'expiredAt';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public function rules(): array
    {
        return [
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
            self::getIp.'.required' => '请设置IP地址,支持IPv4/IPv6',
            self::getReason.'.required' => '请设置拉黑原因',
            self::getExpiredAt.'.required' => '请设置过期时间,空=永久',
            self::getUserType.'.required' => '请设置操作账户类型:admin,system',
            self::getUserId.'.required' => '请设置操作人ID',
        ];
    }
}
