<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSignInEntity')]
class UserSignInEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getSignDate = 'sign_date'; // 签到日期

    public const string getContinuousDays = 'continuous_days'; // 连续签到天数

    public const string getPoints = 'points'; // 本次获得积分

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'signDate', description: '签到日期', type: 'string')]
    private string $signDate;

    #[OA\Property(property: 'continuousDays', description: '连续签到天数', type: 'integer')]
    private int $continuousDays;

    #[OA\Property(property: 'points', description: '本次获得积分', type: 'integer')]
    private int $points;

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
     * 获取签到日期
     */
    public function getSignDate(): string
    {
        return $this->signDate;
    }

    /**
     * 设置签到日期
     */
    public function setSignDate(string $signDate): void
    {
        $this->signDate = $signDate;
    }

    /**
     * 获取连续签到天数
     */
    public function getContinuousDays(): int
    {
        return $this->continuousDays;
    }

    /**
     * 设置连续签到天数
     */
    public function setContinuousDays(int $continuousDays): void
    {
        $this->continuousDays = $continuousDays;
    }

    /**
     * 获取本次获得积分
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * 设置本次获得积分
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
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
