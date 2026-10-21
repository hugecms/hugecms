<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRuleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRuleType, description: '规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class SystemRiskRuleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRuleType = 'ruleType';

    public const string getStatus = 'status';

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
