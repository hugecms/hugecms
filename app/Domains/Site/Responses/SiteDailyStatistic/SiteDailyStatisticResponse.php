<?php

declare(strict_types=1);

namespace App\Domains\Site\Responses\SiteDailyStatistic;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteDailyStatisticResponse')]
class SiteDailyStatisticResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'statDate', description: '统计日期', type: 'string')]
    private string $statDate;

    #[OA\Property(property: 'pv', description: '页面浏览量', type: 'integer')]
    private int $pv;

    #[OA\Property(property: 'uv', description: '独立访客数', type: 'integer')]
    private int $uv;

    #[OA\Property(property: 'newUsers', description: '新增注册用户数', type: 'integer')]
    private int $newUsers;

    #[OA\Property(property: 'orderCount', description: '下单笔数', type: 'integer')]
    private int $orderCount;

    #[OA\Property(property: 'orderAmount', description: '下单金额', type: 'string')]
    private string $orderAmount;

    #[OA\Property(property: 'payCount', description: '支付笔数', type: 'integer')]
    private int $payCount;

    #[OA\Property(property: 'payAmount', description: '支付金额', type: 'string')]
    private string $payAmount;

    #[OA\Property(property: 'refundCount', description: '退款笔数', type: 'integer')]
    private int $refundCount;

    #[OA\Property(property: 'refundAmount', description: '退款金额', type: 'string')]
    private string $refundAmount;

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
     * 获取统计日期
     */
    public function getStatDate(): string
    {
        return $this->statDate;
    }

    /**
     * 设置统计日期
     */
    public function setStatDate(string $statDate): void
    {
        $this->statDate = $statDate;
    }

    /**
     * 获取页面浏览量
     */
    public function getPv(): int
    {
        return $this->pv;
    }

    /**
     * 设置页面浏览量
     */
    public function setPv(int $pv): void
    {
        $this->pv = $pv;
    }

    /**
     * 获取独立访客数
     */
    public function getUv(): int
    {
        return $this->uv;
    }

    /**
     * 设置独立访客数
     */
    public function setUv(int $uv): void
    {
        $this->uv = $uv;
    }

    /**
     * 获取新增注册用户数
     */
    public function getNewUsers(): int
    {
        return $this->newUsers;
    }

    /**
     * 设置新增注册用户数
     */
    public function setNewUsers(int $newUsers): void
    {
        $this->newUsers = $newUsers;
    }

    /**
     * 获取下单笔数
     */
    public function getOrderCount(): int
    {
        return $this->orderCount;
    }

    /**
     * 设置下单笔数
     */
    public function setOrderCount(int $orderCount): void
    {
        $this->orderCount = $orderCount;
    }

    /**
     * 获取下单金额
     */
    public function getOrderAmount(): string
    {
        return $this->orderAmount;
    }

    /**
     * 设置下单金额
     */
    public function setOrderAmount(string $orderAmount): void
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * 获取支付笔数
     */
    public function getPayCount(): int
    {
        return $this->payCount;
    }

    /**
     * 设置支付笔数
     */
    public function setPayCount(int $payCount): void
    {
        $this->payCount = $payCount;
    }

    /**
     * 获取支付金额
     */
    public function getPayAmount(): string
    {
        return $this->payAmount;
    }

    /**
     * 设置支付金额
     */
    public function setPayAmount(string $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    /**
     * 获取退款笔数
     */
    public function getRefundCount(): int
    {
        return $this->refundCount;
    }

    /**
     * 设置退款笔数
     */
    public function setRefundCount(int $refundCount): void
    {
        $this->refundCount = $refundCount;
    }

    /**
     * 获取退款金额
     */
    public function getRefundAmount(): string
    {
        return $this->refundAmount;
    }

    /**
     * 设置退款金额
     */
    public function setRefundAmount(string $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
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
