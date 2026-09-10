<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\ContentModel;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentModelResponse')]
class ContentModelResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '模型名称（显示用，如：招聘信息）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'alias', description: '模型别名（代码/URL用，如：recruitment）', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'tableName', description: '对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）', type: 'string')]
    private string $tableName;

    #[OA\Property(property: 'description', description: '模型描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'isSystem', description: '是否系统内置：1是（不可删除），0否', type: 'integer')]
    private int $isSystem;

    #[OA\Property(property: 'isCommentable', description: '是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）', type: 'integer')]
    private int $isCommentable;

    #[OA\Property(property: 'status', description: '状态：0停用，1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'sort', description: '排序权重', type: 'integer')]
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
     * 获取模型名称（显示用，如：招聘信息）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置模型名称（显示用，如：招聘信息）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取模型别名（代码/URL用，如：recruitment）
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置模型别名（代码/URL用，如：recruitment）
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * 设置对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）
     */
    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * 获取模型描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置模型描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取是否系统内置：1是（不可删除），0否
     */
    public function getIsSystem(): int
    {
        return $this->isSystem;
    }

    /**
     * 设置是否系统内置：1是（不可删除），0否
     */
    public function setIsSystem(int $isSystem): void
    {
        $this->isSystem = $isSystem;
    }

    /**
     * 获取是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）
     */
    public function getIsCommentable(): int
    {
        return $this->isCommentable;
    }

    /**
     * 设置是否允许评论：1是，0否（模型级开关；全站开关见 options.comment_config，两者同时生效取与）
     */
    public function setIsCommentable(int $isCommentable): void
    {
        $this->isCommentable = $isCommentable;
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
     * 获取排序权重
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序权重
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
