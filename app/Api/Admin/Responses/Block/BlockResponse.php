<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Block;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BlockResponse')]
class BlockResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'blockName', description: '区块名称', type: 'string')]
    private string $blockName;

    #[OA\Property(property: 'blockType', description: '区块类型：header/footer/banner/content/sidebar/custom', type: 'string')]
    private string $blockType;

    #[OA\Property(property: 'content', description: '区块内容（HTML/JSON）', type: 'string')]
    private string $content;

    #[OA\Property(property: 'css', description: '自定义CSS样式', type: 'string')]
    private string $css;

    #[OA\Property(property: 'js', description: '自定义JS脚本', type: 'string')]
    private string $js;

    #[OA\Property(property: 'isGlobal', description: '是否全局区块（全站复用）：1是，0否', type: 'integer')]
    private int $isGlobal;

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
     * 获取区块名称
     */
    public function getBlockName(): string
    {
        return $this->blockName;
    }

    /**
     * 设置区块名称
     */
    public function setBlockName(string $blockName): void
    {
        $this->blockName = $blockName;
    }

    /**
     * 获取区块类型：header/footer/banner/content/sidebar/custom
     */
    public function getBlockType(): string
    {
        return $this->blockType;
    }

    /**
     * 设置区块类型：header/footer/banner/content/sidebar/custom
     */
    public function setBlockType(string $blockType): void
    {
        $this->blockType = $blockType;
    }

    /**
     * 获取区块内容（HTML/JSON）
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置区块内容（HTML/JSON）
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取自定义CSS样式
     */
    public function getCss(): string
    {
        return $this->css;
    }

    /**
     * 设置自定义CSS样式
     */
    public function setCss(string $css): void
    {
        $this->css = $css;
    }

    /**
     * 获取自定义JS脚本
     */
    public function getJs(): string
    {
        return $this->js;
    }

    /**
     * 设置自定义JS脚本
     */
    public function setJs(string $js): void
    {
        $this->js = $js;
    }

    /**
     * 获取是否全局区块（全站复用）：1是，0否
     */
    public function getIsGlobal(): int
    {
        return $this->isGlobal;
    }

    /**
     * 设置是否全局区块（全站复用）：1是，0否
     */
    public function setIsGlobal(int $isGlobal): void
    {
        $this->isGlobal = $isGlobal;
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
