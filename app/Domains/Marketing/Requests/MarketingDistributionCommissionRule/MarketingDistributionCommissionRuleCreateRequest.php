<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingDistributionCommissionRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingDistributionCommissionRuleCreateRequest',
    required: [
        self::getScopeType,
        self::getScopeId,
        self::getLevel1Rate,
        self::getLevel2Rate,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getScopeType, description: '范围类型:1=分类默认,2=商品覆盖', type: 'integer'),
        new OA\Property(property: self::getScopeId, description: '目标ID,scope_type=1指向分类,2指向商品', type: 'integer'),
        new OA\Property(property: self::getLevel1Rate, description: '一级佣金比例(%),如5.00表示5%', type: 'string'),
        new OA\Property(property: self::getLevel2Rate, description: '二级佣金比例(%)', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingDistributionCommissionRuleCreateRequest extends FormRequest
{
    public const string getScopeType = 'scopeType';

    public const string getScopeId = 'scopeId';

    public const string getLevel1Rate = 'level1Rate';

    public const string getLevel2Rate = 'level2Rate';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getScopeType => 'required',
            self::getScopeId => 'required',
            self::getLevel1Rate => 'required',
            self::getLevel2Rate => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getScopeType.'.required' => '请设置范围类型:1=分类默认,2=商品覆盖',
            self::getScopeId.'.required' => '请设置目标ID,scope_type=1指向分类,2指向商品',
            self::getLevel1Rate.'.required' => '请设置一级佣金比例(%),如5.00表示5%',
            self::getLevel2Rate.'.required' => '请设置二级佣金比例(%)',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
