<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\ContentPushQueue;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentPushQueueResponse')]
class ContentPushQueueResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '被推送的内容ID', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'pushType', description: '推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss', type: 'string')]
    private string $pushType;

    #[OA\Property(property: 'pushData', description: '推送数据的最终形态（预处理后JSON）', type: 'string')]
    private string $pushData;

    #[OA\Property(property: 'status', description: '状态：pending/processing/success/failed（执行与重试走 Laravel 队列）', type: 'string')]
    private string $status;

    #[OA\Property(property: 'retryCount', description: '已重试次数', type: 'integer')]
    private int $retryCount;

    #[OA\Property(property: 'maxRetries', description: '最大重试次数', type: 'integer')]
    private int $maxRetries;

    #[OA\Property(property: 'errorMessage', description: '失败时的错误信息', type: 'string')]
    private string $errorMessage;

    #[OA\Property(property: 'finishedAt', description: '完成时间', type: 'string')]
    private string $finishedAt;

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
     * 获取被推送的内容ID
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置被推送的内容ID
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss
     */
    public function getPushType(): string
    {
        return $this->pushType;
    }

    /**
     * 设置推送目标：wechat_mp/miniprogram/weibo/baidu_zhanzhang/rss
     */
    public function setPushType(string $pushType): void
    {
        $this->pushType = $pushType;
    }

    /**
     * 获取推送数据的最终形态（预处理后JSON）
     */
    public function getPushData(): string
    {
        return $this->pushData;
    }

    /**
     * 设置推送数据的最终形态（预处理后JSON）
     */
    public function setPushData(string $pushData): void
    {
        $this->pushData = $pushData;
    }

    /**
     * 获取状态：pending/processing/success/failed（执行与重试走 Laravel 队列）
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * 设置状态：pending/processing/success/failed（执行与重试走 Laravel 队列）
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取已重试次数
     */
    public function getRetryCount(): int
    {
        return $this->retryCount;
    }

    /**
     * 设置已重试次数
     */
    public function setRetryCount(int $retryCount): void
    {
        $this->retryCount = $retryCount;
    }

    /**
     * 获取最大重试次数
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * 设置最大重试次数
     */
    public function setMaxRetries(int $maxRetries): void
    {
        $this->maxRetries = $maxRetries;
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
     * 获取完成时间
     */
    public function getFinishedAt(): string
    {
        return $this->finishedAt;
    }

    /**
     * 设置完成时间
     */
    public function setFinishedAt(string $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
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
