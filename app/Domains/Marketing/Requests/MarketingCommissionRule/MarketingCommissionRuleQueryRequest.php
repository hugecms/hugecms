<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingCommissionRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingCommissionRuleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getScopeType, description: '范围类型:1=分类默认,2=商品覆盖', type: 'integer'),
        new OA\Property(property: self::getScopeId, description: '目标ID,scope_type=1指向分类,2指向商品', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingCommissionRuleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getScopeType = 'scopeType';

    public const string getScopeId = 'scopeId';

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
