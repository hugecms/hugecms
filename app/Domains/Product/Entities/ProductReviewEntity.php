<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductReviewEntity')]
class ProductReviewEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID,已购验证依据

    public const string getOrderItemId = 'order_item_id'; // 订单明细ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID,关联product_skus

    public const string getUserId = 'user_id'; // 评价用户ID

    public const string getRating = 'rating'; // 评分:1-5星

    public const string getContent = 'content'; // 评价内容

    public const string getImages = 'images'; // 晒图(JSON数组)

    public const string getAppendContent = 'append_content'; // 追评内容

    public const string getAppendedAt = 'appended_at'; // 追评时间

    public const string getReply = 'reply'; // 商家回复

    public const string getRepliedAt = 'replied_at'; // 商家回复时间

    public const string getIsAnonymous = 'is_anonymous'; // 匿名:0=否,1=是

    public const string getStatus = 'status'; // 状态:0=待审,1=通过,2=拒绝

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID,已购验证依据', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderItemId', description: '订单明细ID', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,关联product_skus', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'userId', description: '评价用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'rating', description: '评分:1-5星', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'content', description: '评价内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '晒图(JSON数组)', type: 'string')]
    private string $images;

    #[OA\Property(property: 'appendContent', description: '追评内容', type: 'string')]
    private string $appendContent;

    #[OA\Property(property: 'appendedAt', description: '追评时间', type: 'string')]
    private string $appendedAt;

    #[OA\Property(property: 'reply', description: '商家回复', type: 'string')]
    private string $reply;

    #[OA\Property(property: 'repliedAt', description: '商家回复时间', type: 'string')]
    private string $repliedAt;

    #[OA\Property(property: 'isAnonymous', description: '匿名:0=否,1=是', type: 'integer')]
    private int $isAnonymous;

    #[OA\Property(property: 'status', description: '状态:0=待审,1=通过,2=拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取订单ID,已购验证依据
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID,已购验证依据
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取订单明细ID
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单明细ID
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    /**
     * 获取商品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取SKU ID,关联product_skus
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID,关联product_skus
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取评价用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置评价用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取评分:1-5星
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * 设置评分:1-5星
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * 获取评价内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置评价内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取晒图(JSON数组)
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置晒图(JSON数组)
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取追评内容
     */
    public function getAppendContent(): string
    {
        return $this->appendContent;
    }

    /**
     * 设置追评内容
     */
    public function setAppendContent(string $appendContent): void
    {
        $this->appendContent = $appendContent;
    }

    /**
     * 获取追评时间
     */
    public function getAppendedAt(): string
    {
        return $this->appendedAt;
    }

    /**
     * 设置追评时间
     */
    public function setAppendedAt(string $appendedAt): void
    {
        $this->appendedAt = $appendedAt;
    }

    /**
     * 获取商家回复
     */
    public function getReply(): string
    {
        return $this->reply;
    }

    /**
     * 设置商家回复
     */
    public function setReply(string $reply): void
    {
        $this->reply = $reply;
    }

    /**
     * 获取商家回复时间
     */
    public function getRepliedAt(): string
    {
        return $this->repliedAt;
    }

    /**
     * 设置商家回复时间
     */
    public function setRepliedAt(string $repliedAt): void
    {
        $this->repliedAt = $repliedAt;
    }

    /**
     * 获取匿名:0=否,1=是
     */
    public function getIsAnonymous(): int
    {
        return $this->isAnonymous;
    }

    /**
     * 设置匿名:0=否,1=是
     */
    public function setIsAnonymous(int $isAnonymous): void
    {
        $this->isAnonymous = $isAnonymous;
    }

    /**
     * 获取状态:0=待审,1=通过,2=拒绝
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待审,1=通过,2=拒绝
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
