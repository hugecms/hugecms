<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'StatisticsDailyEntity')]
class StatisticsDailyEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getStatDate = 'stat_date'; // 统计日期

    public const string getNewContents = 'new_contents'; // 新增内容数

    public const string getPublishedContents = 'published_contents'; // 发布内容数

    public const string getTotalContents = 'total_contents'; // 累计内容总数

    public const string getTotalViews = 'total_views'; // 全站浏览量

    public const string getNewComments = 'new_comments'; // 新增评论数

    public const string getNewUsers = 'new_users'; // 新增注册用户数

    public const string getActiveUsers = 'active_users'; // 活跃用户数（登录/操作）

    public const string getTotalUsers = 'total_users'; // 累计注册用户数

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'statDate', description: '统计日期', type: 'string')]
    private string $statDate;

    #[OA\Property(property: 'newContents', description: '新增内容数', type: 'integer')]
    private int $newContents;

    #[OA\Property(property: 'publishedContents', description: '发布内容数', type: 'integer')]
    private int $publishedContents;

    #[OA\Property(property: 'totalContents', description: '累计内容总数', type: 'integer')]
    private int $totalContents;

    #[OA\Property(property: 'totalViews', description: '全站浏览量', type: 'integer')]
    private int $totalViews;

    #[OA\Property(property: 'newComments', description: '新增评论数', type: 'integer')]
    private int $newComments;

    #[OA\Property(property: 'newUsers', description: '新增注册用户数', type: 'integer')]
    private int $newUsers;

    #[OA\Property(property: 'activeUsers', description: '活跃用户数（登录/操作）', type: 'integer')]
    private int $activeUsers;

    #[OA\Property(property: 'totalUsers', description: '累计注册用户数', type: 'integer')]
    private int $totalUsers;

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
     * 获取统计日期
     */
    public function getStatDate(): string
    {
        return $this->statDate;
    }

    /**
     * 设置统计日期
     */
    public function setStatDate(string $statDate): void
    {
        $this->statDate = $statDate;
    }

    /**
     * 获取新增内容数
     */
    public function getNewContents(): int
    {
        return $this->newContents;
    }

    /**
     * 设置新增内容数
     */
    public function setNewContents(int $newContents): void
    {
        $this->newContents = $newContents;
    }

    /**
     * 获取发布内容数
     */
    public function getPublishedContents(): int
    {
        return $this->publishedContents;
    }

    /**
     * 设置发布内容数
     */
    public function setPublishedContents(int $publishedContents): void
    {
        $this->publishedContents = $publishedContents;
    }

    /**
     * 获取累计内容总数
     */
    public function getTotalContents(): int
    {
        return $this->totalContents;
    }

    /**
     * 设置累计内容总数
     */
    public function setTotalContents(int $totalContents): void
    {
        $this->totalContents = $totalContents;
    }

    /**
     * 获取全站浏览量
     */
    public function getTotalViews(): int
    {
        return $this->totalViews;
    }

    /**
     * 设置全站浏览量
     */
    public function setTotalViews(int $totalViews): void
    {
        $this->totalViews = $totalViews;
    }

    /**
     * 获取新增评论数
     */
    public function getNewComments(): int
    {
        return $this->newComments;
    }

    /**
     * 设置新增评论数
     */
    public function setNewComments(int $newComments): void
    {
        $this->newComments = $newComments;
    }

    /**
     * 获取新增注册用户数
     */
    public function getNewUsers(): int
    {
        return $this->newUsers;
    }

    /**
     * 设置新增注册用户数
     */
    public function setNewUsers(int $newUsers): void
    {
        $this->newUsers = $newUsers;
    }

    /**
     * 获取活跃用户数（登录/操作）
     */
    public function getActiveUsers(): int
    {
        return $this->activeUsers;
    }

    /**
     * 设置活跃用户数（登录/操作）
     */
    public function setActiveUsers(int $activeUsers): void
    {
        $this->activeUsers = $activeUsers;
    }

    /**
     * 获取累计注册用户数
     */
    public function getTotalUsers(): int
    {
        return $this->totalUsers;
    }

    /**
     * 设置累计注册用户数
     */
    public function setTotalUsers(int $totalUsers): void
    {
        $this->totalUsers = $totalUsers;
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
