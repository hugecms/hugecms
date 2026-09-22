<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Ad;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdResponse')]
class AdResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'positionId', description: '所属广告位ID', type: 'integer')]
    private int $positionId;

    #[OA\Property(property: 'title', description: '广告标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'adType', description: '广告类型：image/text/video/html', type: 'string')]
    private string $adType;

    #[OA\Property(property: 'coverImage', description: '广告图片/视频封面URL', type: 'string')]
    private string $coverImage;

    #[OA\Property(property: 'content', description: '广告内容（纯文本或HTML代码）', type: 'string')]
    private string $content;

    #[OA\Property(property: 'linkUrl', description: '广告跳转链接', type: 'string')]
    private string $linkUrl;

    #[OA\Property(property: 'linkTarget', description: '打开方式：0本窗口，1新窗口', type: 'integer')]
    private int $linkTarget;

    #[OA\Property(property: 'sort', description: '展示排序（数值越小越靠前）', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'startTime', description: '投放开始时间（NULL表示立即开始）', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '投放结束时间（NULL表示永久，过期由时间判断）', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'displayLimit', description: '展示次数上限（0不限）', type: 'integer')]
    private int $displayLimit;

    #[OA\Property(property: 'clickLimit', description: '点击次数上限（0不限）', type: 'integer')]
    private int $clickLimit;

    #[OA\Property(property: 'displayCount', description: '实际展示次数', type: 'integer')]
    private int $displayCount;

    #[OA\Property(property: 'clickCount', description: '实际点击次数', type: 'integer')]
    private int $clickCount;

    #[OA\Property(property: 'status', description: '状态：0停用，1启用', type: 'integer')]
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
     * 获取所属广告位ID
     */
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    /**
     * 设置所属广告位ID
     */
    public function setPositionId(int $positionId): void
    {
        $this->positionId = $positionId;
    }

    /**
     * 获取广告标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置广告标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取广告类型：image/text/video/html
     */
    public function getAdType(): string
    {
        return $this->adType;
    }

    /**
     * 设置广告类型：image/text/video/html
     */
    public function setAdType(string $adType): void
    {
        $this->adType = $adType;
    }

    /**
     * 获取广告图片/视频封面URL
     */
    public function getCoverImage(): string
    {
        return $this->coverImage;
    }

    /**
     * 设置广告图片/视频封面URL
     */
    public function setCoverImage(string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    /**
     * 获取广告内容（纯文本或HTML代码）
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置广告内容（纯文本或HTML代码）
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取广告跳转链接
     */
    public function getLinkUrl(): string
    {
        return $this->linkUrl;
    }

    /**
     * 设置广告跳转链接
     */
    public function setLinkUrl(string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    /**
     * 获取打开方式：0本窗口，1新窗口
     */
    public function getLinkTarget(): int
    {
        return $this->linkTarget;
    }

    /**
     * 设置打开方式：0本窗口，1新窗口
     */
    public function setLinkTarget(int $linkTarget): void
    {
        $this->linkTarget = $linkTarget;
    }

    /**
     * 获取展示排序（数值越小越靠前）
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置展示排序（数值越小越靠前）
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取投放开始时间（NULL表示立即开始）
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置投放开始时间（NULL表示立即开始）
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取投放结束时间（NULL表示永久，过期由时间判断）
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置投放结束时间（NULL表示永久，过期由时间判断）
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取展示次数上限（0不限）
     */
    public function getDisplayLimit(): int
    {
        return $this->displayLimit;
    }

    /**
     * 设置展示次数上限（0不限）
     */
    public function setDisplayLimit(int $displayLimit): void
    {
        $this->displayLimit = $displayLimit;
    }

    /**
     * 获取点击次数上限（0不限）
     */
    public function getClickLimit(): int
    {
        return $this->clickLimit;
    }

    /**
     * 设置点击次数上限（0不限）
     */
    public function setClickLimit(int $clickLimit): void
    {
        $this->clickLimit = $clickLimit;
    }

    /**
     * 获取实际展示次数
     */
    public function getDisplayCount(): int
    {
        return $this->displayCount;
    }

    /**
     * 设置实际展示次数
     */
    public function setDisplayCount(int $displayCount): void
    {
        $this->displayCount = $displayCount;
    }

    /**
     * 获取实际点击次数
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * 设置实际点击次数
     */
    public function setClickCount(int $clickCount): void
    {
        $this->clickCount = $clickCount;
    }

    /**
     * 获取状态：0停用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0停用，1启用
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
