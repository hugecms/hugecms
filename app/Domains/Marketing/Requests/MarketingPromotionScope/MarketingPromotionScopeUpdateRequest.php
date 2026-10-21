<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotionScope;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionScopeUpdateRequest',
    required: [
        self::getId,
        self::getPromotionId,
        self::getScopeType,
        self::getTargetId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPromotionId, description: '促销活动ID,关联marketing_promotions', type: 'integer'),
        new OA\Property(property: self::getScopeType, description: '范围类型:category=分类,brand=品牌,product=商品,与父表range_type一致', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID,按scope_type指向分类/品牌/商品', type: 'integer'),
    ]
)]
class MarketingPromotionScopeUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPromotionId = 'promotionId';

    public const string getScopeType = 'scopeType';

    public const string getTargetId = 'targetId';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPromotionId => 'required',
            self::getScopeType => 'required',
            self::getTargetId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPromotionId.'.required' => '请设置促销活动ID,关联marketing_promotions',
            self::getScopeType.'.required' => '请设置范围类型:category=分类,brand=品牌,product=商品,与父表range_type一致',
            self::getTargetId.'.required' => '请设置目标ID,按scope_type指向分类/品牌/商品',
        ];
    }
}
