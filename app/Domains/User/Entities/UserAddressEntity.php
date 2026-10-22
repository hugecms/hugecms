<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAddressEntity')]
class UserAddressEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getReceiverName = 'receiver_name'; // 收货人

    public const string getReceiverPhone = 'receiver_phone'; // 收货电话

    public const string getReceiverRegion = 'receiver_region'; // 省市区(合并展示用)

    public const string getReceiverProvince = 'receiver_province'; // 省

    public const string getReceiverCity = 'receiver_city'; // 市

    public const string getReceiverDistrict = 'receiver_district'; // 区县

    public const string getReceiverProvinceCode = 'receiver_province_code'; // 省行政区划码,物流面单

    public const string getReceiverCityCode = 'receiver_city_code'; // 市行政区划码

    public const string getReceiverDistrictCode = 'receiver_district_code'; // 区县行政区划码

    public const string getReceiverAddress = 'receiver_address'; // 详细地址

    public const string getIsDefault = 'is_default'; // 默认地址:0=否,1=是

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'receiverName', description: '收货人', type: 'string')]
    private string $receiverName;

    #[OA\Property(property: 'receiverPhone', description: '收货电话', type: 'string')]
    private string $receiverPhone;

    #[OA\Property(property: 'receiverRegion', description: '省市区(合并展示用)', type: 'string')]
    private string $receiverRegion;

    #[OA\Property(property: 'receiverProvince', description: '省', type: 'string')]
    private string $receiverProvince;

    #[OA\Property(property: 'receiverCity', description: '市', type: 'string')]
    private string $receiverCity;

    #[OA\Property(property: 'receiverDistrict', description: '区县', type: 'string')]
    private string $receiverDistrict;

    #[OA\Property(property: 'receiverProvinceCode', description: '省行政区划码,物流面单', type: 'string')]
    private string $receiverProvinceCode;

    #[OA\Property(property: 'receiverCityCode', description: '市行政区划码', type: 'string')]
    private string $receiverCityCode;

    #[OA\Property(property: 'receiverDistrictCode', description: '区县行政区划码', type: 'string')]
    private string $receiverDistrictCode;

    #[OA\Property(property: 'receiverAddress', description: '详细地址', type: 'string')]
    private string $receiverAddress;

    #[OA\Property(property: 'isDefault', description: '默认地址:0=否,1=是', type: 'integer')]
    private int $isDefault;

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
     * 获取省市区(合并展示用)
     */
    public function getReceiverRegion(): string
    {
        return $this->receiverRegion;
    }

    /**
     * 设置省市区(合并展示用)
     */
    public function setReceiverRegion(string $receiverRegion): void
    {
        $this->receiverRegion = $receiverRegion;
    }

    /**
     * 获取省
     */
    public function getReceiverProvince(): string
    {
        return $this->receiverProvince;
    }

    /**
     * 设置省
     */
    public function setReceiverProvince(string $receiverProvince): void
    {
        $this->receiverProvince = $receiverProvince;
    }

    /**
     * 获取市
     */
    public function getReceiverCity(): string
    {
        return $this->receiverCity;
    }

    /**
     * 设置市
     */
    public function setReceiverCity(string $receiverCity): void
    {
        $this->receiverCity = $receiverCity;
    }

    /**
     * 获取区县
     */
    public function getReceiverDistrict(): string
    {
        return $this->receiverDistrict;
    }

    /**
     * 设置区县
     */
    public function setReceiverDistrict(string $receiverDistrict): void
    {
        $this->receiverDistrict = $receiverDistrict;
    }

    /**
     * 获取省行政区划码,物流面单
     */
    public function getReceiverProvinceCode(): string
    {
        return $this->receiverProvinceCode;
    }

    /**
     * 设置省行政区划码,物流面单
     */
    public function setReceiverProvinceCode(string $receiverProvinceCode): void
    {
        $this->receiverProvinceCode = $receiverProvinceCode;
    }

    /**
     * 获取市行政区划码
     */
    public function getReceiverCityCode(): string
    {
        return $this->receiverCityCode;
    }

    /**
     * 设置市行政区划码
     */
    public function setReceiverCityCode(string $receiverCityCode): void
    {
        $this->receiverCityCode = $receiverCityCode;
    }

    /**
     * 获取区县行政区划码
     */
    public function getReceiverDistrictCode(): string
    {
        return $this->receiverDistrictCode;
    }

    /**
     * 设置区县行政区划码
     */
    public function setReceiverDistrictCode(string $receiverDistrictCode): void
    {
        $this->receiverDistrictCode = $receiverDistrictCode;
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
