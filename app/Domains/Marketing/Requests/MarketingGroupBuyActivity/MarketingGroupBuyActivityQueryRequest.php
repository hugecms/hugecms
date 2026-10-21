<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyActivityQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSpuId, description: '商品SPU ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingGroupBuyActivityQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSpuId = 'spuId';

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
