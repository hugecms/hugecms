<?php

declare(strict_types=1);

namespace App\Domains\Site\Responses\SiteAd;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteAdResponse')]
class SiteAdResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'positionCode', description: '广告位标识,关联site_ad_positions.code,如:home_top', type: 'string')]
    private string $positionCode;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'image', description: '图片地址', type: 'string')]
    private string $image;

    #[OA\Property(property: 'link', description: '跳转链接', type: 'string')]
    private string $link;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'startTime', description: '投放开始时间', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '投放结束时间', type: 'string')]
    private string $endTime;

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
     * 获取广告位标识,关联site_ad_positions.code,如:home_top
     */
    public function getPositionCode(): string
    {
        return $this->positionCode;
    }

    /**
     * 设置广告位标识,关联site_ad_positions.code,如:home_top
     */
    public function setPositionCode(string $positionCode): void
    {
        $this->positionCode = $positionCode;
    }

    /**
     * 获取标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取图片地址
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * 设置图片地址
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * 获取跳转链接
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * 设置跳转链接
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:0=禁用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=禁用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取投放开始时间
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置投放开始时间
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取投放结束时间
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置投放结束时间
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
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
