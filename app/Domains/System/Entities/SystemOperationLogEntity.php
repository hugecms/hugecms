<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemOperationLogEntity')]
class SystemOperationLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserType = 'user_type'; // 账户类型:admin=后台,user=前台

    public const string getUserId = 'user_id'; // 用户ID

    public const string getUsername = 'username'; // 操作人账号快照

    public const string getTitle = 'title'; // 操作名称,如:删除用户

    public const string getController = 'controller'; // 控制器

    public const string getAction = 'action'; // 方法

    public const string getMethod = 'method'; // 请求方式

    public const string getUrl = 'url'; // 请求地址

    public const string getParams = 'params'; // 请求参数(JSON)

    public const string getStatus = 'status'; // 结果:0=失败,1=成功

    public const string getCostTime = 'cost_time'; // 耗时(毫秒)

    public const string getIp = 'ip'; // 操作IP

    public const string getUserAgent = 'user_agent'; // User-Agent

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userType', description: '账户类型:admin=后台,user=前台', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'username', description: '操作人账号快照', type: 'string')]
    private string $username;

    #[OA\Property(property: 'title', description: '操作名称,如:删除用户', type: 'string')]
    private string $title;

    #[OA\Property(property: 'controller', description: '控制器', type: 'string')]
    private string $controller;

    #[OA\Property(property: 'action', description: '方法', type: 'string')]
    private string $action;

    #[OA\Property(property: 'method', description: '请求方式', type: 'string')]
    private string $method;

    #[OA\Property(property: 'url', description: '请求地址', type: 'string')]
    private string $url;

    #[OA\Property(property: 'params', description: '请求参数(JSON)', type: 'string')]
    private string $params;

    #[OA\Property(property: 'status', description: '结果:0=失败,1=成功', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'costTime', description: '耗时(毫秒)', type: 'integer')]
    private int $costTime;

    #[OA\Property(property: 'ip', description: '操作IP', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'userAgent', description: 'User-Agent', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
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
     * 获取账户类型:admin=后台,user=前台
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置账户类型:admin=后台,user=前台
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
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
     * 获取操作人账号快照
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * 设置操作人账号快照
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * 获取操作名称,如:删除用户
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置操作名称,如:删除用户
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取控制器
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * 设置控制器
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * 获取方法
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * 设置方法
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * 获取请求方式
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * 设置请求方式
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * 获取请求地址
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * 设置请求地址
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * 获取请求参数(JSON)
     */
    public function getParams(): string
    {
        return $this->params;
    }

    /**
     * 设置请求参数(JSON)
     */
    public function setParams(string $params): void
    {
        $this->params = $params;
    }

    /**
     * 获取结果:0=失败,1=成功
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置结果:0=失败,1=成功
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取耗时(毫秒)
     */
    public function getCostTime(): int
    {
        return $this->costTime;
    }

    /**
     * 设置耗时(毫秒)
     */
    public function setCostTime(int $costTime): void
    {
        $this->costTime = $costTime;
    }

    /**
     * 获取操作IP
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置操作IP
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * 获取User-Agent
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置User-Agent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
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
}
