<?php

declare(strict_types=1);

namespace app\domain\system\entity;

use Juling\Foundation\Support\ArrayHelper;

class SystemOperationLogEntity
{
    use ArrayHelper;

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

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private string $userType;

    private int $userId;

    private string $username;

    private string $title;

    private string $controller;

    private string $action;

    private string $method;

    private string $url;

    private string $params;

    private int $status;

    private int $costTime;

    private string $ip;

    private string $userAgent;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getParams(): string
    {
        return $this->params;
    }

    public function setParams(string $params): void
    {
        $this->params = $params;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCostTime(): int
    {
        return $this->costTime;
    }

    public function setCostTime(int $costTime): void
    {
        $this->costTime = $costTime;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
