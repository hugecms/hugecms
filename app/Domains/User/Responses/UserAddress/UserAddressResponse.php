<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserAddress;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAddressResponse')]
class UserAddressResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'receiverName', description: '收货人', type: 'string')]
    private string $receiverName;

    #[OA\Property(property: 'receiverPhone', description: '收货电话', type: 'string')]
    private string $receiverPhone;

    #[OA\Property(property: 'receiverRegion', description: '省市区', type: 'string')]
    private string $receiverRegion;

    #[OA\Property(property: 'receiverAddress', description: '详细地址', type: 'string')]
    private string $receiverAddress;

    #[OA\Property(property: 'isDefault', description: '默认地址:0=否,1=是', type: 'integer')]
    private int $isDefault;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    #[OA\Property(property: 'deleteTime', description: '', type: 'string')]
    private string $deleteTime;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取收货人
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * 设置收货人
     */
    public function setReceiverName(string $receiverName): void
    {
        $this->receiverName = $receiverName;
    }

    /**
     * 获取收货电话
     */
    public function getReceiverPhone(): string
    {
        return $this->receiverPhone;
    }

    /**
     * 设置收货电话
     */
    public function setReceiverPhone(string $receiverPhone): void
    {
        $this->receiverPhone = $receiverPhone;
    }

    /**
     * 获取省市区
     */
    public function getReceiverRegion(): string
    {
        return $this->receiverRegion;
    }

    /**
     * 设置省市区
     */
    public function setReceiverRegion(string $receiverRegion): void
    {
        $this->receiverRegion = $receiverRegion;
    }

    /**
     * 获取详细地址
     */
    public function getReceiverAddress(): string
    {
        return $this->receiverAddress;
    }

    /**
     * 设置详细地址
     */
    public function setReceiverAddress(string $receiverAddress): void
    {
        $this->receiverAddress = $receiverAddress;
    }

    /**
     * 获取默认地址:0=否,1=是
     */
    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    /**
     * 设置默认地址:0=否,1=是
     */
    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * 获取
     */
    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    /**
     * 设置
     */
    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
