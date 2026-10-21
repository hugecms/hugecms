<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingBargainHelperEntity')]
class MarketingBargainHelperEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getRecordId = 'record_id'; // 砍价单ID

    public const string getHelperUserId = 'helper_user_id'; // 帮砍人用户ID

    public const string getCutAmount = 'cut_amount'; // 本刀砍掉金额(与record条件更新同事务)

    public const string getCreateTime = 'create_time'; // 帮砍时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'recordId', description: '砍价单ID', type: 'integer')]
    private int $recordId;

    #[OA\Property(property: 'helperUserId', description: '帮砍人用户ID', type: 'integer')]
    private int $helperUserId;

    #[OA\Property(property: 'cutAmount', description: '本刀砍掉金额(与record条件更新同事务)', type: 'string')]
    private string $cutAmount;

    #[OA\Property(property: 'createTime', description: '帮砍时间', type: 'string')]
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
     * 获取砍价单ID
     */
    public function getRecordId(): int
    {
        return $this->recordId;
    }

    /**
     * 设置砍价单ID
     */
    public function setRecordId(int $recordId): void
    {
        $this->recordId = $recordId;
    }

    /**
     * 获取帮砍人用户ID
     */
    public function getHelperUserId(): int
    {
        return $this->helperUserId;
    }

    /**
     * 设置帮砍人用户ID
     */
    public function setHelperUserId(int $helperUserId): void
    {
        $this->helperUserId = $helperUserId;
    }

    /**
     * 获取本刀砍掉金额(与record条件更新同事务)
     */
    public function getCutAmount(): string
    {
        return $this->cutAmount;
    }

    /**
     * 设置本刀砍掉金额(与record条件更新同事务)
     */
    public function setCutAmount(string $cutAmount): void
    {
        $this->cutAmount = $cutAmount;
    }

    /**
     * 获取帮砍时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置帮砍时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
