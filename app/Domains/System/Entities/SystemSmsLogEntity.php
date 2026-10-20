<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemSmsLogEntity')]
class SystemSmsLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPhone = 'phone'; // 手机号

    public const string getTemplate = 'template'; // 模板标识,如:verify_code

    public const string getContent = 'content'; // 短信内容

    public const string getParams = 'params'; // 模板变量(JSON)

    public const string getGateway = 'gateway'; // 发送网关:aliyun,tencent,qiniu等

    public const string getStatus = 'status'; // 状态:0=发送中,1=成功,2=失败

    public const string getMessageId = 'message_id'; // 网关消息ID

    public const string getError = 'error'; // 失败原因

    public const string getCostTime = 'cost_time'; // 耗时(毫秒)

    public const string getIp = 'ip'; // 触发来源IP

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'template', description: '模板标识,如:verify_code', type: 'string')]
    private string $template;

    #[OA\Property(property: 'content', description: '短信内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'params', description: '模板变量(JSON)', type: 'string')]
    private string $params;

    #[OA\Property(property: 'gateway', description: '发送网关:aliyun,tencent,qiniu等', type: 'string')]
    private string $gateway;

    #[OA\Property(property: 'status', description: '状态:0=发送中,1=成功,2=失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'messageId', description: '网关消息ID', type: 'string')]
    private string $messageId;

    #[OA\Property(property: 'error', description: '失败原因', type: 'string')]
    private string $error;

    #[OA\Property(property: 'costTime', description: '耗时(毫秒)', type: 'integer')]
    private int $costTime;

    #[OA\Property(property: 'ip', description: '触发来源IP', type: 'string')]
    private string $ip;

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
     * 获取手机号
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * 设置手机号
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * 获取模板标识,如:verify_code
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * 设置模板标识,如:verify_code
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * 获取短信内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置短信内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取模板变量(JSON)
     */
    public function getParams(): string
    {
        return $this->params;
    }

    /**
     * 设置模板变量(JSON)
     */
    public function setParams(string $params): void
    {
        $this->params = $params;
    }

    /**
     * 获取发送网关:aliyun,tencent,qiniu等
     */
    public function getGateway(): string
    {
        return $this->gateway;
    }

    /**
     * 设置发送网关:aliyun,tencent,qiniu等
     */
    public function setGateway(string $gateway): void
    {
        $this->gateway = $gateway;
    }

    /**
     * 获取状态:0=发送中,1=成功,2=失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=发送中,1=成功,2=失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取网关消息ID
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * 设置网关消息ID
     */
    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * 获取失败原因
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * 设置失败原因
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * 获取耗时(毫秒)
     */
    public function getCostTime(): int
    {
        return $this->costTime;
    }

    /**
     * 设置耗时(毫秒)
     */
    public function setCostTime(int $costTime): void
    {
        $this->costTime = $costTime;
    }

    /**
     * 获取触发来源IP
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置触发来源IP
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
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
