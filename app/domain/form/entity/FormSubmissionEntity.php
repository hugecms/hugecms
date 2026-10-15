<?php

declare(strict_types=1);

namespace app\domain\form\entity;

use Juling\Foundation\Support\ArrayHelper;

class FormSubmissionEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getFormId = 'form_id'; // 表单ID

    public const string getUserId = 'user_id'; // 提交用户ID,游客为空

    public const string getData = 'data'; // 提交数据(JSON,{字段标识:值})

    public const string getStatus = 'status'; // 处理状态:0=未处理,1=已处理

    public const string getIp = 'ip'; // 提交IP

    public const string getUserAgent = 'user_agent'; // User-Agent

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private int $formId;

    private int $userId;

    private json $data;

    private int $status;

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

    public function getFormId(): int
    {
        return $this->formId;
    }

    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getData(): json
    {
        return $this->data;
    }

    public function setData(json $data): void
    {
        $this->data = $data;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
