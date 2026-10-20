<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemRegionEntity')]
class SystemRegionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级ID,0=省级

    public const string getName = 'name'; // 区域名称

    public const string getLevel = 'level'; // 层级:1=省,2=市,3=区县

    public const string getCode = 'code'; // 行政区划码

    public const string getSort = 'sort'; // 排序

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID,0=省级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '区域名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'level', description: '层级:1=省,2=市,3=区县', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'code', description: '行政区划码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取父级ID,0=省级
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID,0=省级
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取区域名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置区域名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取层级:1=省,2=市,3=区县
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置层级:1=省,2=市,3=区县
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取行政区划码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置行政区划码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
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
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
