<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotionLadder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionLadderUpdateRequest',
    required: [
        self::getId,
        self::getPromotionId,
        self::getMinAmount,
        self::getReduceAmount,
        self::getDiscountRate,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPromotionId, description: '促销活动ID,关联marketing_promotions', type: 'integer'),
        new OA\Property(property: self::getMinAmount, description: '门槛金额,0=无门槛', type: 'string'),
        new OA\Property(property: self::getReduceAmount, description: '满减金额,full_reduce类型必填', type: 'string'),
        new OA\Property(property: self::getDiscountRate, description: '折扣率,如8.5表示85折,discount类型必填', type: 'string'),
    ]
)]
class MarketingPromotionLadderUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPromotionId = 'promotionId';

    public const string getMinAmount = 'minAmount';

    public const string getReduceAmount = 'reduceAmount';

    public const string getDiscountRate = 'discountRate';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPromotionId => 'required',
            self::getMinAmount => 'required',
            self::getReduceAmount => 'required',
            self::getDiscountRate => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPromotionId.'.required' => '请设置促销活动ID,关联marketing_promotions',
            self::getMinAmount.'.required' => '请设置门槛金额,0=无门槛',
            self::getReduceAmount.'.required' => '请设置满减金额,full_reduce类型必填',
            self::getDiscountRate.'.required' => '请设置折扣率,如8.5表示85折,discount类型必填',
        ];
    }
}
