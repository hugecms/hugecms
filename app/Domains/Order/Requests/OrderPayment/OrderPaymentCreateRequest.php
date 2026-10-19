<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderPayment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPaymentCreateRequest',
    required: [
        self::getPaymentNo,
        self::getOrderId,
        self::getPayMethod,
        self::getAmount,
        self::getStatus,
        self::getTradeNo,
        self::getPaidAt,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getPayMethod, description: '支付方式:wechat,alipay', type: 'string'),
        new OA\Property(property: self::getAmount, description: '支付金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待支付,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
        new OA\Property(property: self::getPaidAt, description: '支付成功时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class OrderPaymentCreateRequest extends FormRequest
{
    public const string getPaymentNo = 'paymentNo';

    public const string getOrderId = 'orderId';

    public const string getPayMethod = 'payMethod';

    public const string getAmount = 'amount';

    public const string getStatus = 'status';

    public const string getTradeNo = 'tradeNo';

    public const string getPaidAt = 'paidAt';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getPaymentNo => 'required',
            self::getOrderId => 'required',
            self::getPayMethod => 'required',
            self::getAmount => 'required',
            self::getStatus => 'required',
            self::getTradeNo => 'required',
            self::getPaidAt => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPaymentNo.'.required' => '请设置支付单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getPayMethod.'.required' => '请设置支付方式:wechat,alipay',
            self::getAmount.'.required' => '请设置支付金额',
            self::getStatus.'.required' => '请设置状态:0=待支付,1=成功,2=失败',
            self::getTradeNo.'.required' => '请设置第三方交易号',
            self::getPaidAt.'.required' => '请设置支付成功时间',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
