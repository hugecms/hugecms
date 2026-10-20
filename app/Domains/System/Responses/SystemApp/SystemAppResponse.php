<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemApp;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemAppResponse')]
class SystemAppResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'code', description: '应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android', type: 'string')]
    private string $code;

    #[OA\Property(property: 'name', description: '应用名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '应用类型:web,h5,wechat,app', type: 'string')]
    private string $type;

    #[OA\Property(property: 'appid', description: '微信类应用AppID,非微信应用为空', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'secret', description: '应用密钥,加密存储', type: 'string')]
    private string $secret;

    #[OA\Property(property: 'config', description: '扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等', type: 'string')]
    private string $config;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用,停用即一键下线该端', type: 'integer')]
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
     * 获取应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取应用名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置应用名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取应用类型:web,h5,wechat,app
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置应用类型:web,h5,wechat,app
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取微信类应用AppID,非微信应用为空
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置微信类应用AppID,非微信应用为空
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取应用密钥,加密存储
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * 设置应用密钥,加密存储
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * 获取扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
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
     * 获取状态:0=停用,1=启用,停用即一键下线该端
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用,停用即一键下线该端
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
