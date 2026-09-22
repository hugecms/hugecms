<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\AuditLog;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AuditLogResponse')]
class AuditLogResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '操作用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userName', description: '操作用户名（冗余，防用户被删后无记录）', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'clientIp', description: '客户端IP（支持IPv6）', type: 'string')]
    private string $clientIp;

    #[OA\Property(property: 'userAgent', description: '客户端UA信息', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'requestId', description: '请求追踪ID（关联一次请求的所有日志）', type: 'string')]
    private string $requestId;

    #[OA\Property(property: 'eventType', description: '事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT', type: 'string')]
    private string $eventType;

    #[OA\Property(property: 'targetType', description: '目标类型：content/term/user/attachment/config/comment/form_submission', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '目标ID（可能是数字或UUID）', type: 'string')]
    private string $targetId;

    #[OA\Property(property: 'targetName', description: '目标名称（冗余，便于展示）', type: 'string')]
    private string $targetName;

    #[OA\Property(property: 'oldValue', description: '修改前的数据快照（JSON）', type: 'string')]
    private string $oldValue;

    #[OA\Property(property: 'newValue', description: '修改后的数据快照（JSON）', type: 'string')]
    private string $newValue;

    #[OA\Property(property: 'operationResult', description: '操作结果：0失败，1成功', type: 'integer')]
    private int $operationResult;

    #[OA\Property(property: 'errorMessage', description: '失败时的错误信息', type: 'string')]
    private string $errorMessage;

    #[OA\Property(property: 'createdAt', description: '创建时间（毫秒精度，只增不改）', type: 'string')]
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
     * 获取操作用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置操作用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取操作用户名（冗余，防用户被删后无记录）
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置操作用户名（冗余，防用户被删后无记录）
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取客户端IP（支持IPv6）
     */
    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * 设置客户端IP（支持IPv6）
     */
    public function setClientIp(string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * 获取客户端UA信息
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置客户端UA信息
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * 获取请求追踪ID（关联一次请求的所有日志）
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * 设置请求追踪ID（关联一次请求的所有日志）
     */
    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * 获取事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * 设置事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT
     */
    public function setEventType(string $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * 获取目标类型：content/term/user/attachment/config/comment/form_submission
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型：content/term/user/attachment/config/comment/form_submission
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取目标ID（可能是数字或UUID）
     */
    public function getTargetId(): string
    {
        return $this->targetId;
    }

    /**
     * 设置目标ID（可能是数字或UUID）
     */
    public function setTargetId(string $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取目标名称（冗余，便于展示）
     */
    public function getTargetName(): string
    {
        return $this->targetName;
    }

    /**
     * 设置目标名称（冗余，便于展示）
     */
    public function setTargetName(string $targetName): void
    {
        $this->targetName = $targetName;
    }

    /**
     * 获取修改前的数据快照（JSON）
     */
    public function getOldValue(): string
    {
        return $this->oldValue;
    }

    /**
     * 设置修改前的数据快照（JSON）
     */
    public function setOldValue(string $oldValue): void
    {
        $this->oldValue = $oldValue;
    }

    /**
     * 获取修改后的数据快照（JSON）
     */
    public function getNewValue(): string
    {
        return $this->newValue;
    }

    /**
     * 设置修改后的数据快照（JSON）
     */
    public function setNewValue(string $newValue): void
    {
        $this->newValue = $newValue;
    }

    /**
     * 获取操作结果：0失败，1成功
     */
    public function getOperationResult(): int
    {
        return $this->operationResult;
    }

    /**
     * 设置操作结果：0失败，1成功
     */
    public function setOperationResult(int $operationResult): void
    {
        $this->operationResult = $operationResult;
    }

    /**
     * 获取失败时的错误信息
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * 设置失败时的错误信息
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * 获取创建时间（毫秒精度，只增不改）
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间（毫秒精度，只增不改）
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
