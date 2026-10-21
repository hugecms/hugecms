<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyItemUpdateRequest',
    required: [
        self::getId,
        self::getActivityId,
        self::getSkuId,
        self::getGroupPrice,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '拼团活动ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getGroupPrice, description: '该SKU成团价,下单快照进订单项price', type: 'string'),
    ]
)]
class MarketingGroupBuyItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getSkuId = 'skuId';

    public const string getGroupPrice = 'groupPrice';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getActivityId => 'required',
            self::getSkuId => 'required',
            self::getGroupPrice => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getActivityId.'.required' => '请设置拼团活动ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getGroupPrice.'.required' => '请设置该SKU成团价,下单快照进订单项price',
        ];
    }
}
