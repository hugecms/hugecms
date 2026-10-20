<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentCallbackUpdateRequest',
    required: [
        self::getId,
        self::getPaymentId,
        self::getChannel,
        self::getEventType,
        self::getNotifyId,
        self::getTradeNo,
        self::getRawContent,
        self::getSignVerified,
        self::getStatus,
        self::getProcessedAt,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentId, description: '关联支付单ID,未匹配到支付单时为空', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '渠道:wechat,alipay', type: 'string'),
        new OA\Property(property: self::getEventType, description: '事件类型:payment=支付通知,refund=退款通知', type: 'string'),
        new OA\Property(property: self::getNotifyId, description: '第三方通知唯一ID,幂等去重依据', type: 'string'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
        new OA\Property(property: self::getRawContent, description: '原始报文(JSON)', type: 'string'),
        new OA\Property(property: self::getSignVerified, description: '验签结果:0=未通过,1=通过', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '处理状态:0=未处理,1=已处理,2=异常忽略', type: 'integer'),
        new OA\Property(property: self::getProcessedAt, description: '处理时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class PaymentCallbackUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentId = 'paymentId';

    public const string getChannel = 'channel';

    public const string getEventType = 'eventType';

    public const string getNotifyId = 'notifyId';

    public const string getTradeNo = 'tradeNo';

    public const string getRawContent = 'rawContent';

    public const string getSignVerified = 'signVerified';

    public const string getStatus = 'status';

    public const string getProcessedAt = 'processedAt';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPaymentId => 'required',
            self::getChannel => 'required',
            self::getEventType => 'required',
            self::getNotifyId => 'required',
            self::getTradeNo => 'required',
            self::getRawContent => 'required',
            self::getSignVerified => 'required',
            self::getStatus => 'required',
            self::getProcessedAt => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPaymentId.'.required' => '请设置关联支付单ID,未匹配到支付单时为空',
            self::getChannel.'.required' => '请设置渠道:wechat,alipay',
            self::getEventType.'.required' => '请设置事件类型:payment=支付通知,refund=退款通知',
            self::getNotifyId.'.required' => '请设置第三方通知唯一ID,幂等去重依据',
            self::getTradeNo.'.required' => '请设置第三方交易号',
            self::getRawContent.'.required' => '请设置原始报文(JSON)',
            self::getSignVerified.'.required' => '请设置验签结果:0=未通过,1=通过',
            self::getStatus.'.required' => '请设置处理状态:0=未处理,1=已处理,2=异常忽略',
            self::getProcessedAt.'.required' => '请设置处理时间',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
