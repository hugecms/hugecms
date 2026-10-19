<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductBrandEntity')]
class ProductBrandEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 品牌名称

    public const string getLogo = 'logo'; // 品牌LOGO

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '品牌名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'logo', description: '品牌LOGO', type: 'string')]
    private string $logo;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

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
     * 获取品牌名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置品牌名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取品牌LOGO
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * 设置品牌LOGO
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
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
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
