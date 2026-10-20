<?php

declare(strict_types=1);

namespace App\Domains\Chat\Responses\ChatBotRule;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatBotRuleResponse')]
class ChatBotRuleResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'keyword', description: '命中关键词,消息包含即触发', type: 'string')]
    private string $keyword;

    #[OA\Property(property: 'replyType', description: '回复类型:1=文本,2=FAQ,3=转人工', type: 'integer')]
    private int $replyType;

    #[OA\Property(property: 'content', description: '回复内容,文本回复时用', type: 'string')]
    private string $content;

    #[OA\Property(property: 'faqId', description: 'FAQ ID,FAQ回复时用', type: 'integer')]
    private int $faqId;

    #[OA\Property(property: 'hitCount', description: '命中次数,运营优化依据', type: 'integer')]
    private int $hitCount;

    #[OA\Property(property: 'sort', description: '排序,小者优先匹配', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

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
     * 获取命中关键词,消息包含即触发
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * 设置命中关键词,消息包含即触发
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * 获取回复类型:1=文本,2=FAQ,3=转人工
     */
    public function getReplyType(): int
    {
        return $this->replyType;
    }

    /**
     * 设置回复类型:1=文本,2=FAQ,3=转人工
     */
    public function setReplyType(int $replyType): void
    {
        $this->replyType = $replyType;
    }

    /**
     * 获取回复内容,文本回复时用
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置回复内容,文本回复时用
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取FAQ ID,FAQ回复时用
     */
    public function getFaqId(): int
    {
        return $this->faqId;
    }

    /**
     * 设置FAQ ID,FAQ回复时用
     */
    public function setFaqId(int $faqId): void
    {
        $this->faqId = $faqId;
    }

    /**
     * 获取命中次数,运营优化依据
     */
    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    /**
     * 设置命中次数,运营优化依据
     */
    public function setHitCount(int $hitCount): void
    {
        $this->hitCount = $hitCount;
    }

    /**
     * 获取排序,小者优先匹配
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序,小者优先匹配
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
