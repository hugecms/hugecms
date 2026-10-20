<?php

declare(strict_types=1);

namespace App\Domains\Payment\Responses\PaymentCallback;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentCallbackResponse')]
class PaymentCallbackResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'paymentId', description: '关联支付单ID,未匹配到支付单时为空', type: 'integer')]
    private int $paymentId;

    #[OA\Property(property: 'channel', description: '渠道:wechat,alipay', type: 'string')]
    private string $channel;

    #[OA\Property(property: 'eventType', description: '事件类型:payment=支付通知,refund=退款通知', type: 'string')]
    private string $eventType;

    #[OA\Property(property: 'notifyId', description: '第三方通知唯一ID,幂等去重依据', type: 'string')]
    private string $notifyId;

    #[OA\Property(property: 'tradeNo', description: '第三方交易号', type: 'string')]
    private string $tradeNo;

    #[OA\Property(property: 'rawContent', description: '原始报文(JSON)', type: 'string')]
    private string $rawContent;

    #[OA\Property(property: 'signVerified', description: '验签结果:0=未通过,1=通过', type: 'integer')]
    private int $signVerified;

    #[OA\Property(property: 'status', description: '处理状态:0=未处理,1=已处理,2=异常忽略', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'processedAt', description: '处理时间', type: 'string')]
    private string $processedAt;

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
     * 获取关联支付单ID,未匹配到支付单时为空
     */
    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    /**
     * 设置关联支付单ID,未匹配到支付单时为空
     */
    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * 获取渠道:wechat,alipay
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * 设置渠道:wechat,alipay
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * 获取事件类型:payment=支付通知,refund=退款通知
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * 设置事件类型:payment=支付通知,refund=退款通知
     */
    public function setEventType(string $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * 获取第三方通知唯一ID,幂等去重依据
     */
    public function getNotifyId(): string
    {
        return $this->notifyId;
    }

    /**
     * 设置第三方通知唯一ID,幂等去重依据
     */
    public function setNotifyId(string $notifyId): void
    {
        $this->notifyId = $notifyId;
    }

    /**
     * 获取第三方交易号
     */
    public function getTradeNo(): string
    {
        return $this->tradeNo;
    }

    /**
     * 设置第三方交易号
     */
    public function setTradeNo(string $tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    /**
     * 获取原始报文(JSON)
     */
    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /**
     * 设置原始报文(JSON)
     */
    public function setRawContent(string $rawContent): void
    {
        $this->rawContent = $rawContent;
    }

    /**
     * 获取验签结果:0=未通过,1=通过
     */
    public function getSignVerified(): int
    {
        return $this->signVerified;
    }

    /**
     * 设置验签结果:0=未通过,1=通过
     */
    public function setSignVerified(int $signVerified): void
    {
        $this->signVerified = $signVerified;
    }

    /**
     * 获取处理状态:0=未处理,1=已处理,2=异常忽略
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置处理状态:0=未处理,1=已处理,2=异常忽略
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取处理时间
     */
    public function getProcessedAt(): string
    {
        return $this->processedAt;
    }

    /**
     * 设置处理时间
     */
    public function setProcessedAt(string $processedAt): void
    {
        $this->processedAt = $processedAt;
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
