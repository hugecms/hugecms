<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ApiAccessLogEntity')]
class ApiAccessLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getClientId = 'client_id'; // 客户端ID

    public const string getEndpoint = 'endpoint'; // API端点（如：/api/v1/contents）

    public const string getMethod = 'method'; // HTTP方法：GET/POST/PUT/DELETE

    public const string getRequestIp = 'request_ip'; // 请求IP

    public const string getResponseStatus = 'response_status'; // HTTP响应状态码

    public const string getResponseTimeMs = 'response_time_ms'; // 接口响应耗时（毫秒）

    public const string getCreatedAt = 'created_at'; // 请求时间（只增不改）

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'clientId', description: '客户端ID', type: 'string')]
    private string $clientId;

    #[OA\Property(property: 'endpoint', description: 'API端点（如：/api/v1/contents）', type: 'string')]
    private string $endpoint;

    #[OA\Property(property: 'method', description: 'HTTP方法：GET/POST/PUT/DELETE', type: 'string')]
    private string $method;

    #[OA\Property(property: 'requestIp', description: '请求IP', type: 'string')]
    private string $requestIp;

    #[OA\Property(property: 'responseStatus', description: 'HTTP响应状态码', type: 'integer')]
    private int $responseStatus;

    #[OA\Property(property: 'responseTimeMs', description: '接口响应耗时（毫秒）', type: 'integer')]
    private int $responseTimeMs;

    #[OA\Property(property: 'createdAt', description: '请求时间（只增不改）', type: 'string')]
    private string $createdAt;

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
     * 获取客户端ID
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * 设置客户端ID
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * 获取API端点（如：/api/v1/contents）
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * 设置API端点（如：/api/v1/contents）
     */
    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * 获取HTTP方法：GET/POST/PUT/DELETE
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * 设置HTTP方法：GET/POST/PUT/DELETE
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * 获取请求IP
     */
    public function getRequestIp(): string
    {
        return $this->requestIp;
    }

    /**
     * 设置请求IP
     */
    public function setRequestIp(string $requestIp): void
    {
        $this->requestIp = $requestIp;
    }

    /**
     * 获取HTTP响应状态码
     */
    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }

    /**
     * 设置HTTP响应状态码
     */
    public function setResponseStatus(int $responseStatus): void
    {
        $this->responseStatus = $responseStatus;
    }

    /**
     * 获取接口响应耗时（毫秒）
     */
    public function getResponseTimeMs(): int
    {
        return $this->responseTimeMs;
    }

    /**
     * 设置接口响应耗时（毫秒）
     */
    public function setResponseTimeMs(int $responseTimeMs): void
    {
        $this->responseTimeMs = $responseTimeMs;
    }

    /**
     * 获取请求时间（只增不改）
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置请求时间（只增不改）
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
