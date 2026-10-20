<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Responses\ShippingMethod;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingMethodResponse')]
class ShippingMethodResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '配送方式名称,如:顺丰标快', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '承运商/运费模板标识', type: 'string')]
    private string $code;

    #[OA\Property(property: 'description', description: '配送说明', type: 'string')]
    private string $description;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

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
     * 获取配送方式名称,如:顺丰标快
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置配送方式名称,如:顺丰标快
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取承运商/运费模板标识
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置承运商/运费模板标识
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取配送说明
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置配送说明
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
