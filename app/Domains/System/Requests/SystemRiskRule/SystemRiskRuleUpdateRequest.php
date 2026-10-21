<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRuleUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getRuleType,
        self::getConditionExpr,
        self::getAction,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '规则名称', type: 'string'),
        new OA\Property(property: self::getRuleType, description: '规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级', type: 'integer'),
        new OA\Property(property: self::getConditionExpr, description: '条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}', type: 'string'),
        new OA\Property(property: self::getAction, description: '处置动作:1=拦截,2=标记放行', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class SystemRiskRuleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getRuleType = 'ruleType';

    public const string getConditionExpr = 'conditionExpr';

    public const string getAction = 'action';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getRuleType => 'required',
            self::getConditionExpr => 'required',
            self::getAction => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置规则名称',
            self::getRuleType.'.required' => '请设置规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级',
            self::getConditionExpr.'.required' => '请设置条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}',
            self::getAction.'.required' => '请设置处置动作:1=拦截,2=标记放行',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
