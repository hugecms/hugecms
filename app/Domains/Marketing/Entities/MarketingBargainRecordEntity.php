<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingBargainRecordEntity')]
class MarketingBargainRecordEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getBargainNo = 'bargain_no'; // 砍价单号,全局唯一

    public const string getItemId = 'item_id'; // 砍价场次商品ID

    public const string getUserId = 'user_id'; // 发起人用户ID

    public const string getCurrentPrice = 'current_price'; // 当前价,逐刀递减且&gt;=floor_price由条件更新保证

    public const string getCutCount = 'cut_count'; // 已砍刀数

    public const string getStatus = 'status'; // 状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消

    public const string getExpireTime = 'expire_time'; // 砍价截止时间,超时扫描依据

    public const string getSuccessTime = 'success_time'; // 到底价时间

    public const string getOrderNo = 'order_no'; // 成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'bargainNo', description: '砍价单号,全局唯一', type: 'string')]
    private string $bargainNo;

    #[OA\Property(property: 'itemId', description: '砍价场次商品ID', type: 'integer')]
    private int $itemId;

    #[OA\Property(property: 'userId', description: '发起人用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'currentPrice', description: '当前价,逐刀递减且&gt;=floor_price由条件更新保证', type: 'string')]
    private string $currentPrice;

    #[OA\Property(property: 'cutCount', description: '已砍刀数', type: 'integer')]
    private int $cutCount;

    #[OA\Property(property: 'status', description: '状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'expireTime', description: '砍价截止时间,超时扫描依据', type: 'string')]
    private string $expireTime;

    #[OA\Property(property: 'successTime', description: '到底价时间', type: 'string')]
    private string $successTime;

    #[OA\Property(property: 'orderNo', description: '成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束', type: 'string')]
    private string $orderNo;

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
     * 获取砍价单号,全局唯一
     */
    public function getBargainNo(): string
    {
        return $this->bargainNo;
    }

    /**
     * 设置砍价单号,全局唯一
     */
    public function setBargainNo(string $bargainNo): void
    {
        $this->bargainNo = $bargainNo;
    }

    /**
     * 获取砍价场次商品ID
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * 设置砍价场次商品ID
     */
    public function setItemId(int $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * 获取发起人用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置发起人用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取当前价,逐刀递减且&gt;=floor_price由条件更新保证
     */
    public function getCurrentPrice(): string
    {
        return $this->currentPrice;
    }

    /**
     * 设置当前价,逐刀递减且&gt;=floor_price由条件更新保证
     */
    public function setCurrentPrice(string $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    /**
     * 获取已砍刀数
     */
    public function getCutCount(): int
    {
        return $this->cutCount;
    }

    /**
     * 设置已砍刀数
     */
    public function setCutCount(int $cutCount): void
    {
        $this->cutCount = $cutCount;
    }

    /**
     * 获取状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取砍价截止时间,超时扫描依据
     */
    public function getExpireTime(): string
    {
        return $this->expireTime;
    }

    /**
     * 设置砍价截止时间,超时扫描依据
     */
    public function setExpireTime(string $expireTime): void
    {
        $this->expireTime = $expireTime;
    }

    /**
     * 获取到底价时间
     */
    public function getSuccessTime(): string
    {
        return $this->successTime;
    }

    /**
     * 设置到底价时间
     */
    public function setSuccessTime(string $successTime): void
    {
        $this->successTime = $successTime;
    }

    /**
     * 获取成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
     */
    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    /**
     * 设置成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束
     */
    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
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
