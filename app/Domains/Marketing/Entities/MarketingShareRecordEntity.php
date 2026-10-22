<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingShareRecordEntity')]
class MarketingShareRecordEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getSharerUserId = 'sharer_user_id'; // 分享者用户ID

    public const string getSpuId = 'spu_id'; // 分享对象商品SPU ID,NULL=整店/页面分享

    public const string getShareChannel = 'share_channel'; // 分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群

    public const string getSceneValue = 'scene_value'; // 小程序场景值,落地追踪

    public const string getShareCode = 'share_code'; // 使用的推广码,关联users.share_code

    public const string getCreateTime = 'create_time'; // 分享时间,归因窗口起点

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'sharerUserId', description: '分享者用户ID', type: 'integer')]
    private int $sharerUserId;

    #[OA\Property(property: 'spuId', description: '分享对象商品SPU ID,NULL=整店/页面分享', type: 'integer')]
    private int $spuId;

    #[OA\Property(property: 'shareChannel', description: '分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群', type: 'integer')]
    private int $shareChannel;

    #[OA\Property(property: 'sceneValue', description: '小程序场景值,落地追踪', type: 'string')]
    private string $sceneValue;

    #[OA\Property(property: 'shareCode', description: '使用的推广码,关联users.share_code', type: 'string')]
    private string $shareCode;

    #[OA\Property(property: 'createTime', description: '分享时间,归因窗口起点', type: 'string')]
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
     * 获取分享者用户ID
     */
    public function getSharerUserId(): int
    {
        return $this->sharerUserId;
    }

    /**
     * 设置分享者用户ID
     */
    public function setSharerUserId(int $sharerUserId): void
    {
        $this->sharerUserId = $sharerUserId;
    }

    /**
     * 获取分享对象商品SPU ID,NULL=整店/页面分享
     */
    public function getSpuId(): int
    {
        return $this->spuId;
    }

    /**
     * 设置分享对象商品SPU ID,NULL=整店/页面分享
     */
    public function setSpuId(int $spuId): void
    {
        $this->spuId = $spuId;
    }

    /**
     * 获取分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群
     */
    public function getShareChannel(): int
    {
        return $this->shareChannel;
    }

    /**
     * 设置分享渠道:1=小程序卡片,2=海报图片,3=复制链接,4=朋友圈/社群
     */
    public function setShareChannel(int $shareChannel): void
    {
        $this->shareChannel = $shareChannel;
    }

    /**
     * 获取小程序场景值,落地追踪
     */
    public function getSceneValue(): string
    {
        return $this->sceneValue;
    }

    /**
     * 设置小程序场景值,落地追踪
     */
    public function setSceneValue(string $sceneValue): void
    {
        $this->sceneValue = $sceneValue;
    }

    /**
     * 获取使用的推广码,关联users.share_code
     */
    public function getShareCode(): string
    {
        return $this->shareCode;
    }

    /**
     * 设置使用的推广码,关联users.share_code
     */
    public function setShareCode(string $shareCode): void
    {
        $this->shareCode = $shareCode;
    }

    /**
     * 获取分享时间,归因窗口起点
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置分享时间,归因窗口起点
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
