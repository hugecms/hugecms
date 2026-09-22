<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\AdPosition;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdPositionResponse')]
class AdPositionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '广告位名称（如：首页Banner）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '广告位代码（如：home_banner，模板调用用）', type: 'string')]
    private string $code;

    #[OA\Property(property: 'width', description: '建议宽度（像素）', type: 'integer')]
    private int $width;

    #[OA\Property(property: 'height', description: '建议高度（像素）', type: 'integer')]
    private int $height;

    #[OA\Property(property: 'adType', description: '支持的广告类型：image/text/video/html', type: 'string')]
    private string $adType;

    #[OA\Property(property: 'maxCount', description: '该广告位最多展示广告数量', type: 'integer')]
    private int $maxCount;

    #[OA\Property(property: 'description', description: '广告位描述', type: 'string')]
    private string $description;

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
     * 获取广告位名称（如：首页Banner）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置广告位名称（如：首页Banner）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取广告位代码（如：home_banner，模板调用用）
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置广告位代码（如：home_banner，模板调用用）
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取建议宽度（像素）
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * 设置建议宽度（像素）
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * 获取建议高度（像素）
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * 设置建议高度（像素）
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * 获取支持的广告类型：image/text/video/html
     */
    public function getAdType(): string
    {
        return $this->adType;
    }

    /**
     * 设置支持的广告类型：image/text/video/html
     */
    public function setAdType(string $adType): void
    {
        $this->adType = $adType;
    }

    /**
     * 获取该广告位最多展示广告数量
     */
    public function getMaxCount(): int
    {
        return $this->maxCount;
    }

    /**
     * 设置该广告位最多展示广告数量
     */
    public function setMaxCount(int $maxCount): void
    {
        $this->maxCount = $maxCount;
    }

    /**
     * 获取广告位描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置广告位描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
