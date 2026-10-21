<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotionLadder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionLadderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPromotionId, description: '促销活动ID,关联marketing_promotions', type: 'integer'),
        new OA\Property(property: self::getMinAmount, description: '门槛金额,0=无门槛', type: 'string'),
    ]
)]
class MarketingPromotionLadderQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPromotionId = 'promotionId';

    public const string getMinAmount = 'minAmount';

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
