<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingFlashSaleLog;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingFlashSaleLogResponse')]
class MarketingFlashSaleLogResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'flashSaleItemId', description: '秒杀商品ID,关联marketing_flash_sale_items', type: 'integer')]
    private int $flashSaleItemId;

    #[OA\Property(property: 'orderId', description: '关联订单ID,扣减与回补均关联订单', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'changeQuantity', description: '变动数量,正数扣减,负数回补', type: 'integer')]
    private int $changeQuantity;

    #[OA\Property(property: 'beforeSoldStock', description: '变动前已售量', type: 'integer')]
    private int $beforeSoldStock;

    #[OA\Property(property: 'afterSoldStock', description: '变动后已售量', type: 'integer')]
    private int $afterSoldStock;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
    private string $createTime;

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
     * 获取秒杀商品ID,关联marketing_flash_sale_items
     */
    public function getFlashSaleItemId(): int
    {
        return $this->flashSaleItemId;
    }

    /**
     * 设置秒杀商品ID,关联marketing_flash_sale_items
     */
    public function setFlashSaleItemId(int $flashSaleItemId): void
    {
        $this->flashSaleItemId = $flashSaleItemId;
    }

    /**
     * 获取关联订单ID,扣减与回补均关联订单
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置关联订单ID,扣减与回补均关联订单
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取变动数量,正数扣减,负数回补
     */
    public function getChangeQuantity(): int
    {
        return $this->changeQuantity;
    }

    /**
     * 设置变动数量,正数扣减,负数回补
     */
    public function setChangeQuantity(int $changeQuantity): void
    {
        $this->changeQuantity = $changeQuantity;
    }

    /**
     * 获取变动前已售量
     */
    public function getBeforeSoldStock(): int
    {
        return $this->beforeSoldStock;
    }

    /**
     * 设置变动前已售量
     */
    public function setBeforeSoldStock(int $beforeSoldStock): void
    {
        $this->beforeSoldStock = $beforeSoldStock;
    }

    /**
     * 获取变动后已售量
     */
    public function getAfterSoldStock(): int
    {
        return $this->afterSoldStock;
    }

    /**
     * 设置变动后已售量
     */
    public function setAfterSoldStock(int $afterSoldStock): void
    {
        $this->afterSoldStock = $afterSoldStock;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
