<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\FormSubmission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormSubmissionResponse')]
class FormSubmissionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'formId', description: '关联表单模板ID', type: 'integer')]
    private int $formId;

    #[OA\Property(property: 'submissionData', description: '用户提交的具体表单数据（JSON）', type: 'string')]
    private string $submissionData;

    #[OA\Property(property: 'submitterIp', description: '提交者IP', type: 'string')]
    private string $submitterIp;

    #[OA\Property(property: 'userAgent', description: '提交者UA', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
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
     * 获取关联表单模板ID
     */
    public function getFormId(): int
    {
        return $this->formId;
    }

    /**
     * 设置关联表单模板ID
     */
    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    /**
     * 获取用户提交的具体表单数据（JSON）
     */
    public function getSubmissionData(): string
    {
        return $this->submissionData;
    }

    /**
     * 设置用户提交的具体表单数据（JSON）
     */
    public function setSubmissionData(string $submissionData): void
    {
        $this->submissionData = $submissionData;
    }

    /**
     * 获取提交者IP
     */
    public function getSubmitterIp(): string
    {
        return $this->submitterIp;
    }

    /**
     * 设置提交者IP
     */
    public function setSubmitterIp(string $submitterIp): void
    {
        $this->submitterIp = $submitterIp;
    }

    /**
     * 获取提交者UA
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置提交者UA
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
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
}
