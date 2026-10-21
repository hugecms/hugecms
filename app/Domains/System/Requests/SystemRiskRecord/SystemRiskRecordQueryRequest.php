<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRecordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '触发用户ID', type: 'integer'),
        new OA\Property(property: self::getRuleId, description: '命中规则ID,关联system_risk_rules', type: 'integer'),
        new OA\Property(property: self::getObjectType, description: '对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)', type: 'integer'),
        new OA\Property(property: self::getObjectNo, description: '对象单号,如提现单号/订单号', type: 'string'),
    ]
)]
class SystemRiskRecordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getRuleId = 'ruleId';

    public const string getObjectType = 'objectType';

    public const string getObjectNo = 'objectNo';

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
