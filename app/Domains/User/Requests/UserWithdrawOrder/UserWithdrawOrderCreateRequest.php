<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderCreateRequest',
    required: [
        self::getWithdrawNo,
        self::getUserId,
        self::getAmount,
        self::getChannel,
        self::getChannelOrderNo,
        self::getAccountType,
        self::getAccountName,
        self::getAccountNo,
        self::getStatus,
        self::getUserNote,
        self::getAdminNote,
        self::getFailReason,
        self::getAuditAt,
        self::getPaidAt,
    ],
    properties: [
        new OA\Property(property: self::getWithdrawNo, description: '提现单号,应用层生成', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '提现金额', type: 'string'),
        new OA\Property(property: self::getChannel, description: '打款渠道:manual=人工线下打款,微信商家转账后续扩展', type: 'string'),
        new OA\Property(property: self::getChannelOrderNo, description: '渠道打款单号,回填防重复打款', type: 'string'),
        new OA\Property(property: self::getAccountType, description: '收款方式:bank=银行卡,wechat=微信,alipay=支付宝', type: 'string'),
        new OA\Property(property: self::getAccountName, description: '收款人姓名', type: 'string'),
        new OA\Property(property: self::getAccountNo, description: '收款账号', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退', type: 'integer'),
        new OA\Property(property: self::getUserNote, description: '用户备注', type: 'string'),
        new OA\Property(property: self::getAdminNote, description: '审核备注', type: 'string'),
        new OA\Property(property: self::getFailReason, description: '打款失败原因', type: 'string'),
        new OA\Property(property: self::getAuditAt, description: '审核时间', type: 'string'),
        new OA\Property(property: self::getPaidAt, description: '打款标记时间(成功或失败)', type: 'string'),
    ]
)]
class UserWithdrawOrderCreateRequest extends FormRequest
{
    public const string getWithdrawNo = 'withdrawNo';

    public const string getUserId = 'userId';

    public const string getAmount = 'amount';

    public const string getChannel = 'channel';

    public const string getChannelOrderNo = 'channelOrderNo';

    public const string getAccountType = 'accountType';

    public const string getAccountName = 'accountName';

    public const string getAccountNo = 'accountNo';

    public const string getStatus = 'status';

    public const string getUserNote = 'userNote';

    public const string getAdminNote = 'adminNote';

    public const string getFailReason = 'failReason';

    public const string getAuditAt = 'auditAt';

    public const string getPaidAt = 'paidAt';

    public function rules(): array
    {
        return [
            self::getWithdrawNo => 'required',
            self::getUserId => 'required',
            self::getAmount => 'required',
            self::getChannel => 'required',
            self::getChannelOrderNo => 'required',
            self::getAccountType => 'required',
            self::getAccountName => 'required',
            self::getAccountNo => 'required',
            self::getStatus => 'required',
            self::getUserNote => 'required',
            self::getAdminNote => 'required',
            self::getFailReason => 'required',
            self::getAuditAt => 'required',
            self::getPaidAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getWithdrawNo.'.required' => '请设置提现单号,应用层生成',
            self::getUserId.'.required' => '请设置用户ID',
            self::getAmount.'.required' => '请设置提现金额',
            self::getChannel.'.required' => '请设置打款渠道:manual=人工线下打款,微信商家转账后续扩展',
            self::getChannelOrderNo.'.required' => '请设置渠道打款单号,回填防重复打款',
            self::getAccountType.'.required' => '请设置收款方式:bank=银行卡,wechat=微信,alipay=支付宝',
            self::getAccountName.'.required' => '请设置收款人姓名',
            self::getAccountNo.'.required' => '请设置收款账号',
            self::getStatus.'.required' => '请设置状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退',
            self::getUserNote.'.required' => '请设置用户备注',
            self::getAdminNote.'.required' => '请设置审核备注',
            self::getFailReason.'.required' => '请设置打款失败原因',
            self::getAuditAt.'.required' => '请设置审核时间',
            self::getPaidAt.'.required' => '请设置打款标记时间(成功或失败)',
        ];
    }
}
