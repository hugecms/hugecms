<?php

declare(strict_types=1);

namespace App\Domains\Chat\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatFaqEntity')]
class ChatFaqEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCategory = 'category'; // 分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户

    public const string getQuestion = 'question'; // 问题

    public const string getAnswer = 'answer'; // 答案

    public const string getViews = 'views'; // 浏览量

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=隐藏,1=显示

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'category', description: '分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户', type: 'string')]
    private string $category;

    #[OA\Property(property: 'question', description: '问题', type: 'string')]
    private string $question;

    #[OA\Property(property: 'answer', description: '答案', type: 'string')]
    private string $answer;

    #[OA\Property(property: 'views', description: '浏览量', type: 'integer')]
    private int $views;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer')]
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
     * 获取分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * 设置分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * 获取问题
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * 设置问题
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    /**
     * 获取答案
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * 设置答案
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * 获取浏览量
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * 设置浏览量
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
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
     * 获取状态:0=隐藏,1=显示
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=隐藏,1=显示
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
