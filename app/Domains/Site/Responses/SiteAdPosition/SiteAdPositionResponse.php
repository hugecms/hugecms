<?php

declare(strict_types=1);

namespace App\Domains\Site\Responses\SiteAdPosition;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteAdPositionResponse')]
class SiteAdPositionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'code', description: '广告位标识,如:home_top', type: 'string')]
    private string $code;

    #[OA\Property(property: 'name', description: '广告位名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'description', description: '说明,如:首页顶部,建议尺寸750x300', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
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
     * 获取广告位标识,如:home_top
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置广告位标识,如:home_top
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取广告位名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置广告位名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取说明,如:首页顶部,建议尺寸750x300
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置说明,如:首页顶部,建议尺寸750x300
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
