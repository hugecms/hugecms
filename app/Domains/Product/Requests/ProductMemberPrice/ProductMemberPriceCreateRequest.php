<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductMemberPrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductMemberPriceCreateRequest',
    required: [
        self::getProductId,
        self::getRankId,
        self::getPrice,
    ],
    properties: [
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getRankId, description: '会员等级ID', type: 'integer'),
        new OA\Property(property: self::getPrice, description: '等级价', type: 'string'),
    ]
)]
class ProductMemberPriceCreateRequest extends FormRequest
{
    public const string getProductId = 'productId';

    public const string getRankId = 'rankId';

    public const string getPrice = 'price';

    public function rules(): array
    {
        return [
            self::getProductId => 'required',
            self::getRankId => 'required',
            self::getPrice => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getProductId.'.required' => '请设置商品ID',
            self::getRankId.'.required' => '请设置会员等级ID',
            self::getPrice.'.required' => '请设置等级价',
        ];
    }
}
