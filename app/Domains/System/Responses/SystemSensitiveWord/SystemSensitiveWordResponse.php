<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemSensitiveWord;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemSensitiveWordResponse')]
class SystemSensitiveWordResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'word', description: '敏感词', type: 'string')]
    private string $word;

    #[OA\Property(property: 'replacement', description: '替换词,空=直接拦截', type: 'string')]
    private string $replacement;

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
     * 获取敏感词
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * 设置敏感词
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    /**
     * 获取替换词,空=直接拦截
     */
    public function getReplacement(): string
    {
        return $this->replacement;
    }

    /**
     * 设置替换词,空=直接拦截
     */
    public function setReplacement(string $replacement): void
    {
        $this->replacement = $replacement;
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
