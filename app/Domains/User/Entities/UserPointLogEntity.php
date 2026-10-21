<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserPointLogEntity')]
class UserPointLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getBizType = 'biz_type'; // 业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送

    public const string getChangePoints = 'change_points'; // 积分变动,正数入负数出

    public const string getPointsAfter = 'points_after'; // 变动后积分余额快照

    public const string getBizNo = 'biz_no'; // 关联业务单号

    public const string getDescription = 'description'; // 变动说明

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'bizType', description: '业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送', type: 'integer')]
    private int $bizType;

    #[OA\Property(property: 'changePoints', description: '积分变动,正数入负数出', type: 'integer')]
    private int $changePoints;

    #[OA\Property(property: 'pointsAfter', description: '变动后积分余额快照', type: 'integer')]
    private int $pointsAfter;

    #[OA\Property(property: 'bizNo', description: '关联业务单号', type: 'string')]
    private string $bizNo;

    #[OA\Property(property: 'description', description: '变动说明', type: 'string')]
    private string $description;

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
     * 获取业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送
     */
    public function getBizType(): int
    {
        return $this->bizType;
    }

    /**
     * 设置业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送
     */
    public function setBizType(int $bizType): void
    {
        $this->bizType = $bizType;
    }

    /**
     * 获取积分变动,正数入负数出
     */
    public function getChangePoints(): int
    {
        return $this->changePoints;
    }

    /**
     * 设置积分变动,正数入负数出
     */
    public function setChangePoints(int $changePoints): void
    {
        $this->changePoints = $changePoints;
    }

    /**
     * 获取变动后积分余额快照
     */
    public function getPointsAfter(): int
    {
        return $this->pointsAfter;
    }

    /**
     * 设置变动后积分余额快照
     */
    public function setPointsAfter(int $pointsAfter): void
    {
        $this->pointsAfter = $pointsAfter;
    }

    /**
     * 获取关联业务单号
     */
    public function getBizNo(): string
    {
        return $this->bizNo;
    }

    /**
     * 设置关联业务单号
     */
    public function setBizNo(string $bizNo): void
    {
        $this->bizNo = $bizNo;
    }

    /**
     * 获取变动说明
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置变动说明
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
