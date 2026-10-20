<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductReviewQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '评价用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审,1=通过,2=拒绝', type: 'integer'),
    ]
)]
class ProductReviewQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getUserId = 'userId';

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
