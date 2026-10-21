<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '拼团活动ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
    ]
)]
class MarketingGroupBuyItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getSkuId = 'skuId';

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
