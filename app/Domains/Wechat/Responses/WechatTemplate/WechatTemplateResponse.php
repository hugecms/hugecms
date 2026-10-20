<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Responses\WechatTemplate;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatTemplateResponse')]
class WechatTemplateResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '所属应用AppID,公众号或小程序', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'scene', description: '业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等', type: 'string')]
    private string $scene;

    #[OA\Property(property: 'type', description: '模板类型:1=公众号模板消息,2=小程序订阅消息', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'templateId', description: '微信模板ID', type: 'string')]
    private string $templateId;

    #[OA\Property(property: 'name', description: '模板名称,运营识别用', type: 'string')]
    private string $name;

    #[OA\Property(property: 'fields', description: '字段映射(JSON):业务字段到模板参数的映射', type: 'string')]
    private string $fields;

    #[OA\Property(property: 'page', description: '点击跳转页面路径,如:pages/order/detail?id=1', type: 'string')]
    private string $page;

    #[OA\Property(property: 'sendCount', description: '发送次数', type: 'integer')]
    private int $sendCount;

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
     * 获取所属应用AppID,公众号或小程序
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置所属应用AppID,公众号或小程序
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
     */
    public function getScene(): string
    {
        return $this->scene;
    }

    /**
     * 设置业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等
     */
    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    /**
     * 获取模板类型:1=公众号模板消息,2=小程序订阅消息
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置模板类型:1=公众号模板消息,2=小程序订阅消息
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取微信模板ID
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    /**
     * 设置微信模板ID
     */
    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取模板名称,运营识别用
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置模板名称,运营识别用
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取字段映射(JSON):业务字段到模板参数的映射
     */
    public function getFields(): string
    {
        return $this->fields;
    }

    /**
     * 设置字段映射(JSON):业务字段到模板参数的映射
     */
    public function setFields(string $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * 获取点击跳转页面路径,如:pages/order/detail?id=1
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * 设置点击跳转页面路径,如:pages/order/detail?id=1
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * 获取发送次数
     */
    public function getSendCount(): int
    {
        return $this->sendCount;
    }

    /**
     * 设置发送次数
     */
    public function setSendCount(int $sendCount): void
    {
        $this->sendCount = $sendCount;
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
