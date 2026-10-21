<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserWithdrawOrder;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserWithdrawOrderResponse')]
class UserWithdrawOrderResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'withdrawNo', description: '提现单号,应用层生成', type: 'string')]
    private string $withdrawNo;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'amount', description: '提现金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'channel', description: '打款渠道:manual=人工线下打款,微信商家转账后续扩展', type: 'string')]
    private string $channel;

    #[OA\Property(property: 'channelOrderNo', description: '渠道打款单号,回填防重复打款', type: 'string')]
    private string $channelOrderNo;

    #[OA\Property(property: 'accountType', description: '收款方式:bank=银行卡,wechat=微信,alipay=支付宝', type: 'string')]
    private string $accountType;

    #[OA\Property(property: 'accountName', description: '收款人姓名', type: 'string')]
    private string $accountName;

    #[OA\Property(property: 'accountNo', description: '收款账号', type: 'string')]
    private string $accountNo;

    #[OA\Property(property: 'status', description: '状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'userNote', description: '用户备注', type: 'string')]
    private string $userNote;

    #[OA\Property(property: 'adminNote', description: '审核备注', type: 'string')]
    private string $adminNote;

    #[OA\Property(property: 'failReason', description: '打款失败原因', type: 'string')]
    private string $failReason;

    #[OA\Property(property: 'auditAt', description: '审核时间', type: 'string')]
    private string $auditAt;

    #[OA\Property(property: 'paidAt', description: '打款标记时间(成功或失败)', type: 'string')]
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
     * 获取提现单号,应用层生成
     */
    public function getWithdrawNo(): string
    {
        return $this->withdrawNo;
    }

    /**
     * 设置提现单号,应用层生成
     */
    public function setWithdrawNo(string $withdrawNo): void
    {
        $this->withdrawNo = $withdrawNo;
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
     * 获取提现金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置提现金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取打款渠道:manual=人工线下打款,微信商家转账后续扩展
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * 设置打款渠道:manual=人工线下打款,微信商家转账后续扩展
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * 获取渠道打款单号,回填防重复打款
     */
    public function getChannelOrderNo(): string
    {
        return $this->channelOrderNo;
    }

    /**
     * 设置渠道打款单号,回填防重复打款
     */
    public function setChannelOrderNo(string $channelOrderNo): void
    {
        $this->channelOrderNo = $channelOrderNo;
    }

    /**
     * 获取收款方式:bank=银行卡,wechat=微信,alipay=支付宝
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }

    /**
     * 设置收款方式:bank=银行卡,wechat=微信,alipay=支付宝
     */
    public function setAccountType(string $accountType): void
    {
        $this->accountType = $accountType;
    }

    /**
     * 获取收款人姓名
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * 设置收款人姓名
     */
    public function setAccountName(string $accountName): void
    {
        $this->accountName = $accountName;
    }

    /**
     * 获取收款账号
     */
    public function getAccountNo(): string
    {
        return $this->accountNo;
    }

    /**
     * 设置收款账号
     */
    public function setAccountNo(string $accountNo): void
    {
        $this->accountNo = $accountNo;
    }

    /**
     * 获取状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取用户备注
     */
    public function getUserNote(): string
    {
        return $this->userNote;
    }

    /**
     * 设置用户备注
     */
    public function setUserNote(string $userNote): void
    {
        $this->userNote = $userNote;
    }

    /**
     * 获取审核备注
     */
    public function getAdminNote(): string
    {
        return $this->adminNote;
    }

    /**
     * 设置审核备注
     */
    public function setAdminNote(string $adminNote): void
    {
        $this->adminNote = $adminNote;
    }

    /**
     * 获取打款失败原因
     */
    public function getFailReason(): string
    {
        return $this->failReason;
    }

    /**
     * 设置打款失败原因
     */
    public function setFailReason(string $failReason): void
    {
        $this->failReason = $failReason;
    }

    /**
     * 获取审核时间
     */
    public function getAuditAt(): string
    {
        return $this->auditAt;
    }

    /**
     * 设置审核时间
     */
    public function setAuditAt(string $auditAt): void
    {
        $this->auditAt = $auditAt;
    }

    /**
     * 获取打款标记时间(成功或失败)
     */
    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    /**
     * 设置打款标记时间(成功或失败)
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
