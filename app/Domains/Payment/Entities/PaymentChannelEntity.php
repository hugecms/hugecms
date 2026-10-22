<?php

declare(strict_types=1);

namespace App\Domains\Payment\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentChannelEntity')]
class PaymentChannelEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCode = 'code'; // 渠道标识:wechat=微信支付,alipay=支付宝,balance=余额

    public const string getAppid = 'appid'; // 应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号

    public const string getName = 'name'; // 渠道名称

    public const string getConfig = 'config'; // 渠道配置(JSON):商户号/密钥等

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getSort = 'sort'; // 排序

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'code', description: '渠道标识:wechat=微信支付,alipay=支付宝,balance=余额', type: 'string')]
    private string $code;

    #[OA\Property(property: 'appid', description: '应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'name', description: '渠道名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'config', description: '渠道配置(JSON):商户号/密钥等', type: 'string')]
    private string $config;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

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
     * 获取渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置渠道标识:wechat=微信支付,alipay=支付宝,balance=余额
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取渠道名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置渠道名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取渠道配置(JSON):商户号/密钥等
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置渠道配置(JSON):商户号/密钥等
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
