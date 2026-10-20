<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentCreateRequest',
    required: [
        self::getPaymentNo,
        self::getUserId,
        self::getBizType,
        self::getBizId,
        self::getPayMethod,
        self::getAppid,
        self::getAmount,
        self::getStatus,
        self::getTradeNo,
        self::getPaidAt,
    ],
    properties: [
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getUserId, description: '付款用户ID', type: 'integer'),
        new OA\Property(property: self::getBizType, description: '业务类型:order=订单支付,recharge=余额充值', type: 'string'),
        new OA\Property(property: self::getBizId, description: '业务单据ID,如:订单ID/充值申请ID', type: 'integer'),
        new OA\Property(property: self::getPayMethod, description: '支付方式:wechat,alipay,balance', type: 'string'),
        new OA\Property(property: self::getAppid, description: '发起支付的应用AppID,关联system_apps,多端回调定位依据', type: 'string'),
        new OA\Property(property: self::getAmount, description: '支付金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待支付,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
        new OA\Property(property: self::getPaidAt, description: '支付成功时间', type: 'string'),
    ]
)]
class PaymentCreateRequest extends FormRequest
{
    public const string getPaymentNo = 'paymentNo';

    public const string getUserId = 'userId';

    public const string getBizType = 'bizType';

    public const string getBizId = 'bizId';

    public const string getPayMethod = 'payMethod';

    public const string getAppid = 'appid';

    public const string getAmount = 'amount';

    public const string getStatus = 'status';

    public const string getTradeNo = 'tradeNo';

    public const string getPaidAt = 'paidAt';

    public function rules(): array
    {
        return [
            self::getPaymentNo => 'required',
            self::getUserId => 'required',
            self::getBizType => 'required',
            self::getBizId => 'required',
            self::getPayMethod => 'required',
            self::getAppid => 'required',
            self::getAmount => 'required',
            self::getStatus => 'required',
            self::getTradeNo => 'required',
            self::getPaidAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPaymentNo.'.required' => '请设置支付单号',
            self::getUserId.'.required' => '请设置付款用户ID',
            self::getBizType.'.required' => '请设置业务类型:order=订单支付,recharge=余额充值',
            self::getBizId.'.required' => '请设置业务单据ID,如:订单ID/充值申请ID',
            self::getPayMethod.'.required' => '请设置支付方式:wechat,alipay,balance',
            self::getAppid.'.required' => '请设置发起支付的应用AppID,关联system_apps,多端回调定位依据',
            self::getAmount.'.required' => '请设置支付金额',
            self::getStatus.'.required' => '请设置状态:0=待支付,1=成功,2=失败',
            self::getTradeNo.'.required' => '请设置第三方交易号',
            self::getPaidAt.'.required' => '请设置支付成功时间',
        ];
    }
}
