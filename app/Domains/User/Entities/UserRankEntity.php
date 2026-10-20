<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserRankEntity')]
class UserRankEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 等级名称,如:普通会员

    public const string getMinPoints = 'min_points'; // 升级所需最低积分

    public const string getMaxPoints = 'max_points'; // 积分上限,0=不限(最高级)

    public const string getDiscount = 'discount'; // 会员折扣,100=原价,95=95折

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '等级名称,如:普通会员', type: 'string')]
    private string $name;

    #[OA\Property(property: 'minPoints', description: '升级所需最低积分', type: 'integer')]
    private int $minPoints;

    #[OA\Property(property: 'maxPoints', description: '积分上限,0=不限(最高级)', type: 'integer')]
    private int $maxPoints;

    #[OA\Property(property: 'discount', description: '会员折扣,100=原价,95=95折', type: 'integer')]
    private int $discount;

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
     * 获取等级名称,如:普通会员
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置等级名称,如:普通会员
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取升级所需最低积分
     */
    public function getMinPoints(): int
    {
        return $this->minPoints;
    }

    /**
     * 设置升级所需最低积分
     */
    public function setMinPoints(int $minPoints): void
    {
        $this->minPoints = $minPoints;
    }

    /**
     * 获取积分上限,0=不限(最高级)
     */
    public function getMaxPoints(): int
    {
        return $this->maxPoints;
    }

    /**
     * 设置积分上限,0=不限(最高级)
     */
    public function setMaxPoints(int $maxPoints): void
    {
        $this->maxPoints = $maxPoints;
    }

    /**
     * 获取会员折扣,100=原价,95=95折
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    /**
     * 设置会员折扣,100=原价,95=95折
     */
    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
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
