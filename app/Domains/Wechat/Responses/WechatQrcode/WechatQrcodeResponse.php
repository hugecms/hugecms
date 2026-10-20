<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Responses\WechatQrcode;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatQrcodeResponse')]
class WechatQrcodeResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '二维码所属应用AppID,公众号带参码或小程序码', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'sceneStr', description: '场景值,同一应用下唯一', type: 'string')]
    private string $sceneStr;

    #[OA\Property(property: 'name', description: '渠道码名称,如:分销员张三专属码', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '类型:distributor=分销邀请,product=商品推广,channel=渠道投放', type: 'string')]
    private string $type;

    #[OA\Property(property: 'targetId', description: '绑定目标ID,分销码=分销员用户ID,商品码=商品ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'scanCount', description: '扫码次数', type: 'integer')]
    private int $scanCount;

    #[OA\Property(property: 'registerCount', description: '扫码注册转化数', type: 'integer')]
    private int $registerCount;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
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
     * 获取二维码所属应用AppID,公众号带参码或小程序码
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置二维码所属应用AppID,公众号带参码或小程序码
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取场景值,同一应用下唯一
     */
    public function getSceneStr(): string
    {
        return $this->sceneStr;
    }

    /**
     * 设置场景值,同一应用下唯一
     */
    public function setSceneStr(string $sceneStr): void
    {
        $this->sceneStr = $sceneStr;
    }

    /**
     * 获取渠道码名称,如:分销员张三专属码
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置渠道码名称,如:分销员张三专属码
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取类型:distributor=分销邀请,product=商品推广,channel=渠道投放
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:distributor=分销邀请,product=商品推广,channel=渠道投放
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取绑定目标ID,分销码=分销员用户ID,商品码=商品ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置绑定目标ID,分销码=分销员用户ID,商品码=商品ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取扫码次数
     */
    public function getScanCount(): int
    {
        return $this->scanCount;
    }

    /**
     * 设置扫码次数
     */
    public function setScanCount(int $scanCount): void
    {
        $this->scanCount = $scanCount;
    }

    /**
     * 获取扫码注册转化数
     */
    public function getRegisterCount(): int
    {
        return $this->registerCount;
    }

    /**
     * 设置扫码注册转化数
     */
    public function setRegisterCount(int $registerCount): void
    {
        $this->registerCount = $registerCount;
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
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
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
