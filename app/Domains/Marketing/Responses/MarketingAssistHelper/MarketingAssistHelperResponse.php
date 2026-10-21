<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingAssistHelper;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingAssistHelperResponse')]
class MarketingAssistHelperResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'recordId', description: '助力参与记录ID', type: 'integer')]
    private int $recordId;

    #[OA\Property(property: 'helperUserId', description: '助力人用户ID', type: 'integer')]
    private int $helperUserId;

    #[OA\Property(property: 'createTime', description: '助力时间', type: 'string')]
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
     * 获取助力参与记录ID
     */
    public function getRecordId(): int
    {
        return $this->recordId;
    }

    /**
     * 设置助力参与记录ID
     */
    public function setRecordId(int $recordId): void
    {
        $this->recordId = $recordId;
    }

    /**
     * 获取助力人用户ID
     */
    public function getHelperUserId(): int
    {
        return $this->helperUserId;
    }

    /**
     * 设置助力人用户ID
     */
    public function setHelperUserId(int $helperUserId): void
    {
        $this->helperUserId = $helperUserId;
    }

    /**
     * 获取助力时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置助力时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
