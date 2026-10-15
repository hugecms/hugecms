<?php

declare(strict_types=1);

namespace app\domain\system\response\systemOperationLog;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemOperationLogResponse')]
class SystemOperationLogResponse
{
    use ArrayHelper;

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
