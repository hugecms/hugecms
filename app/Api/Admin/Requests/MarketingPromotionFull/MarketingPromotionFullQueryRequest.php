<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\MarketingPromotionFull;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionFullQueryRequest',
    required: [
        self::getId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: '促销活动ID', type: 'integer'),
    ]
)]
class MarketingPromotionFullQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置促销活动ID',
        ];
    }
}
