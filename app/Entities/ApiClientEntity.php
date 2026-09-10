<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ApiClientEntity')]
class ApiClientEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getClientName = 'client_name'; // 客户端名称（如：小程序应用）

    public const string getClientId = 'client_id'; // 客户端ID（公钥）

    public const string getClientSecret = 'client_secret'; // 客户端密钥（应用层AES加密后存储）

    public const string getApiKey = 'api_key'; // API Key（用于简化认证）

    public const string getGrantType = 'grant_type'; // 授权类型：client_credentials/password/authorization_code

    public const string getIpWhitelist = 'ip_whitelist'; // IP白名单（JSON数组）

    public const string getRateLimit = 'rate_limit'; // 每分钟请求数限制

    public const string getStatus = 'status'; // 状态：0禁用，1启用

    public const string getLastUsedAt = 'last_used_at'; // 最后使用时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'clientName', description: '客户端名称（如：小程序应用）', type: 'string')]
    private string $clientName;

    #[OA\Property(property: 'clientId', description: '客户端ID（公钥）', type: 'string')]
    private string $clientId;

    #[OA\Property(property: 'clientSecret', description: '客户端密钥（应用层AES加密后存储）', type: 'string')]
    private string $clientSecret;

    #[OA\Property(property: 'apiKey', description: 'API Key（用于简化认证）', type: 'string')]
    private string $apiKey;

    #[OA\Property(property: 'grantType', description: '授权类型：client_credentials/password/authorization_code', type: 'string')]
    private string $grantType;

    #[OA\Property(property: 'ipWhitelist', description: 'IP白名单（JSON数组）', type: 'string')]
    private string $ipWhitelist;

    #[OA\Property(property: 'rateLimit', description: '每分钟请求数限制', type: 'integer')]
    private int $rateLimit;

    #[OA\Property(property: 'status', description: '状态：0禁用，1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'lastUsedAt', description: '最后使用时间', type: 'string')]
    private string $lastUsedAt;

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
     * 获取客户端名称（如：小程序应用）
     */
    public function getClientName(): string
    {
        return $this->clientName;
    }

    /**
     * 设置客户端名称（如：小程序应用）
     */
    public function setClientName(string $clientName): void
    {
        $this->clientName = $clientName;
    }

    /**
     * 获取客户端ID（公钥）
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * 设置客户端ID（公钥）
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * 获取客户端密钥（应用层AES加密后存储）
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * 设置客户端密钥（应用层AES加密后存储）
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * 获取API Key（用于简化认证）
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * 设置API Key（用于简化认证）
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * 获取授权类型：client_credentials/password/authorization_code
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * 设置授权类型：client_credentials/password/authorization_code
     */
    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    /**
     * 获取IP白名单（JSON数组）
     */
    public function getIpWhitelist(): string
    {
        return $this->ipWhitelist;
    }

    /**
     * 设置IP白名单（JSON数组）
     */
    public function setIpWhitelist(string $ipWhitelist): void
    {
        $this->ipWhitelist = $ipWhitelist;
    }

    /**
     * 获取每分钟请求数限制
     */
    public function getRateLimit(): int
    {
        return $this->rateLimit;
    }

    /**
     * 设置每分钟请求数限制
     */
    public function setRateLimit(int $rateLimit): void
    {
        $this->rateLimit = $rateLimit;
    }

    /**
     * 获取状态：0禁用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0禁用，1启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取最后使用时间
     */
    public function getLastUsedAt(): string
    {
        return $this->lastUsedAt;
    }

    /**
     * 设置最后使用时间
     */
    public function setLastUsedAt(string $lastUsedAt): void
    {
        $this->lastUsedAt = $lastUsedAt;
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
