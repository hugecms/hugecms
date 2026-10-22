<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserFavorite;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserFavoriteResponse')]
class UserFavoriteResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'targetType', description: '目标类型:article=文章,product=商品', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '目标ID', type: 'integer')]
    private int $targetId;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取目标类型:article=文章,product=商品
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型:article=文章,product=商品
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取目标ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置目标ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
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
