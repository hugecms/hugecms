<?php

declare(strict_types=1);

namespace App\Domains\Payment\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentEntity')]
class PaymentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPaymentNo = 'payment_no'; // 支付单号

    public const string getUserId = 'user_id'; // 付款用户ID

    public const string getBizType = 'biz_type'; // 业务类型:order=订单支付,recharge=余额充值

    public const string getBizId = 'biz_id'; // 业务单据ID,如:订单ID/充值申请ID

    public const string getPayMethod = 'pay_method'; // 支付方式:wechat,alipay,balance

    public const string getAppid = 'appid'; // 发起支付的应用AppID,关联system_apps,多端回调定位依据

    public const string getAmount = 'amount'; // 支付金额

    public const string getStatus = 'status'; // 状态:0=待支付,1=成功,2=失败

    public const string getTradeNo = 'trade_no'; // 第三方交易号

    public const string getPaidAt = 'paid_at'; // 支付成功时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'paymentNo', description: '支付单号', type: 'string')]
    private string $paymentNo;

    #[OA\Property(property: 'userId', description: '付款用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'bizType', description: '业务类型:order=订单支付,recharge=余额充值', type: 'string')]
    private string $bizType;

    #[OA\Property(property: 'bizId', description: '业务单据ID,如:订单ID/充值申请ID', type: 'integer')]
    private int $bizId;

    #[OA\Property(property: 'payMethod', description: '支付方式:wechat,alipay,balance', type: 'string')]
    private string $payMethod;

    #[OA\Property(property: 'appid', description: '发起支付的应用AppID,关联system_apps,多端回调定位依据', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'amount', description: '支付金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'status', description: '状态:0=待支付,1=成功,2=失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'tradeNo', description: '第三方交易号', type: 'string')]
    private string $tradeNo;

    #[OA\Property(property: 'paidAt', description: '支付成功时间', type: 'string')]
    private string $paidAt;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取支付单号
     */
    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    /**
     * 设置支付单号
     */
    public function setPaymentNo(string $paymentNo): void
    {
        $this->paymentNo = $paymentNo;
    }

    /**
     * 获取付款用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置付款用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取业务类型:order=订单支付,recharge=余额充值
     */
    public function getBizType(): string
    {
        return $this->bizType;
    }

    /**
     * 设置业务类型:order=订单支付,recharge=余额充值
     */
    public function setBizType(string $bizType): void
    {
        $this->bizType = $bizType;
    }

    /**
     * 获取业务单据ID,如:订单ID/充值申请ID
     */
    public function getBizId(): int
    {
        return $this->bizId;
    }

    /**
     * 设置业务单据ID,如:订单ID/充值申请ID
     */
    public function setBizId(int $bizId): void
    {
        $this->bizId = $bizId;
    }

    /**
     * 获取支付方式:wechat,alipay,balance
     */
    public function getPayMethod(): string
    {
        return $this->payMethod;
    }

    /**
     * 设置支付方式:wechat,alipay,balance
     */
    public function setPayMethod(string $payMethod): void
    {
        $this->payMethod = $payMethod;
    }

    /**
     * 获取发起支付的应用AppID,关联system_apps,多端回调定位依据
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置发起支付的应用AppID,关联system_apps,多端回调定位依据
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取支付金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置支付金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取状态:0=待支付,1=成功,2=失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待支付,1=成功,2=失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
     * 获取支付成功时间
     */
    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    /**
     * 设置支付成功时间
     */
    public function setPaidAt(string $paidAt): void
    {
        $this->paidAt = $paidAt;
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
