<?php

declare(strict_types=1);

namespace App\Domains\Product\Responses\ProductStockReminder;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductStockReminderResponse')]
class ProductStockReminderResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '登记用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,0=单规格/不限规格', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'status', description: '状态:0=待提醒,1=已提醒,2=已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'notifiedAt', description: '提醒时间', type: 'string')]
    private string $notifiedAt;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取登记用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置登记用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取SKU ID,0=单规格/不限规格
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID,0=单规格/不限规格
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取状态:0=待提醒,1=已提醒,2=已取消
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待提醒,1=已提醒,2=已取消
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取提醒时间
     */
    public function getNotifiedAt(): string
    {
        return $this->notifiedAt;
    }

    /**
     * 设置提醒时间
     */
    public function setNotifiedAt(string $notifiedAt): void
    {
        $this->notifiedAt = $notifiedAt;
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
}
