<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\MarketingPromotionFull;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionFullDestroyRequest',
    required: [
        self::getIds,
    ],
    properties: [
        new OA\Property(property: self::getIds, description: '促销活动ID集合', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
class MarketingPromotionFullDestroyRequest extends FormRequest
{
    public const string getIds = 'ids';

    public function rules(): array
    {
        return [
            self::getIds => 'required|array|min:1',
            self::getIds.'.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getIds.'.required' => '请设置要删除的促销活动ID',
            self::getIds.'.min' => '请至少选择一个促销活动',
        ];
    }
}
