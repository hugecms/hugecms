<?php

declare(strict_types=1);

namespace App\Domains\Form\Responses\FormSubmission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormSubmissionResponse')]
class FormSubmissionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'formId', description: '表单ID', type: 'integer')]
    private int $formId;

    #[OA\Property(property: 'userId', description: '提交用户ID,游客为空', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'data', description: '提交数据(JSON,{字段标识:值})', type: 'string')]
    private string $data;

    #[OA\Property(property: 'status', description: '处理状态:0=未处理,1=已处理', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'ip', description: '提交IP', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'userAgent', description: 'User-Agent', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取表单ID
     */
    public function getFormId(): int
    {
        return $this->formId;
    }

    /**
     * 设置表单ID
     */
    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    /**
     * 获取提交用户ID,游客为空
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置提交用户ID,游客为空
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取提交数据(JSON,{字段标识:值})
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * 设置提交数据(JSON,{字段标识:值})
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * 获取处理状态:0=未处理,1=已处理
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置处理状态:0=未处理,1=已处理
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取提交IP
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置提交IP
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
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
