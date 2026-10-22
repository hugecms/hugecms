<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductReviewUpdateRequest',
    required: [
        self::getId,
        self::getOrderId,
        self::getOrderItemId,
        self::getProductId,
        self::getSkuId,
        self::getUserId,
        self::getRating,
        self::getContent,
        self::getImages,
        self::getAppendContent,
        self::getAppendedAt,
        self::getReply,
        self::getRepliedAt,
        self::getIsAnonymous,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID,已购验证依据', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,关联product_skus', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '评价用户ID', type: 'integer'),
        new OA\Property(property: self::getRating, description: '评分:1-5星', type: 'integer'),
        new OA\Property(property: self::getContent, description: '评价内容', type: 'string'),
        new OA\Property(property: self::getImages, description: '晒图(JSON数组)', type: 'string'),
        new OA\Property(property: self::getAppendContent, description: '追评内容', type: 'string'),
        new OA\Property(property: self::getAppendedAt, description: '追评时间', type: 'string'),
        new OA\Property(property: self::getReply, description: '商家回复', type: 'string'),
        new OA\Property(property: self::getRepliedAt, description: '商家回复时间', type: 'string'),
        new OA\Property(property: self::getIsAnonymous, description: '匿名:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审,1=通过,2=拒绝', type: 'integer'),
    ]
)]
class ProductReviewUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderId = 'orderId';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getUserId = 'userId';

    public const string getRating = 'rating';

    public const string getContent = 'content';

    public const string getImages = 'images';

    public const string getAppendContent = 'appendContent';

    public const string getAppendedAt = 'appendedAt';

    public const string getReply = 'reply';

    public const string getRepliedAt = 'repliedAt';

    public const string getIsAnonymous = 'isAnonymous';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOrderId => 'required',
            self::getOrderItemId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getUserId => 'required',
            self::getRating => 'required',
            self::getContent => 'required',
            self::getImages => 'required',
            self::getAppendContent => 'required',
            self::getAppendedAt => 'required',
            self::getReply => 'required',
            self::getRepliedAt => 'required',
            self::getIsAnonymous => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOrderId.'.required' => '请设置订单ID,已购验证依据',
            self::getOrderItemId.'.required' => '请设置订单明细ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,关联product_skus',
            self::getUserId.'.required' => '请设置评价用户ID',
            self::getRating.'.required' => '请设置评分:1-5星',
            self::getContent.'.required' => '请设置评价内容',
            self::getImages.'.required' => '请设置晒图(JSON数组)',
            self::getAppendContent.'.required' => '请设置追评内容',
            self::getAppendedAt.'.required' => '请设置追评时间',
            self::getReply.'.required' => '请设置商家回复',
            self::getRepliedAt.'.required' => '请设置商家回复时间',
            self::getIsAnonymous.'.required' => '请设置匿名:0=否,1=是',
            self::getStatus.'.required' => '请设置状态:0=待审,1=通过,2=拒绝',
        ];
    }
}
