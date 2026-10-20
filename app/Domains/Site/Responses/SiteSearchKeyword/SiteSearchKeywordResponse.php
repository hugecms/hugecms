<?php

declare(strict_types=1);

namespace App\Domains\Site\Responses\SiteSearchKeyword;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteSearchKeywordResponse')]
class SiteSearchKeywordResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'keyword', description: '搜索词', type: 'string')]
    private string $keyword;

    #[OA\Property(property: 'searchCount', description: '搜索次数', type: 'integer')]
    private int $searchCount;

    #[OA\Property(property: 'resultCount', description: '最近一次结果数,0=无结果', type: 'integer')]
    private int $resultCount;

    #[OA\Property(property: 'lastSearchedAt', description: '最后搜索时间', type: 'string')]
    private string $lastSearchedAt;

    #[OA\Property(property: 'isHot', description: '人工推荐热词:0=否,1=是', type: 'integer')]
    private int $isHot;

    #[OA\Property(property: 'status', description: '状态:0=屏蔽,1=正常', type: 'integer')]
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
     * 获取搜索词
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * 设置搜索词
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * 获取搜索次数
     */
    public function getSearchCount(): int
    {
        return $this->searchCount;
    }

    /**
     * 设置搜索次数
     */
    public function setSearchCount(int $searchCount): void
    {
        $this->searchCount = $searchCount;
    }

    /**
     * 获取最近一次结果数,0=无结果
     */
    public function getResultCount(): int
    {
        return $this->resultCount;
    }

    /**
     * 设置最近一次结果数,0=无结果
     */
    public function setResultCount(int $resultCount): void
    {
        $this->resultCount = $resultCount;
    }

    /**
     * 获取最后搜索时间
     */
    public function getLastSearchedAt(): string
    {
        return $this->lastSearchedAt;
    }

    /**
     * 设置最后搜索时间
     */
    public function setLastSearchedAt(string $lastSearchedAt): void
    {
        $this->lastSearchedAt = $lastSearchedAt;
    }

    /**
     * 获取人工推荐热词:0=否,1=是
     */
    public function getIsHot(): int
    {
        return $this->isHot;
    }

    /**
     * 设置人工推荐热词:0=否,1=是
     */
    public function setIsHot(int $isHot): void
    {
        $this->isHot = $isHot;
    }

    /**
     * 获取状态:0=屏蔽,1=正常
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=屏蔽,1=正常
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
