<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundCreateRequest',
    required: [
        self::getRefundNo,
        self::getOrderId,
        self::getPaymentId,
        self::getAmount,
        self::getReason,
        self::getStatus,
        self::getRefundedAt,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getPaymentId, description: '支付单ID', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '退款金额', type: 'string'),
        new OA\Property(property: self::getReason, description: '退款原因', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审核,1=退款中,2=已退款,3=已拒绝', type: 'integer'),
        new OA\Property(property: self::getRefundedAt, description: '退款完成时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class OrderRefundCreateRequest extends FormRequest
{
    public const string getRefundNo = 'refundNo';

    public const string getOrderId = 'orderId';

    public const string getPaymentId = 'paymentId';

    public const string getAmount = 'amount';

    public const string getReason = 'reason';

    public const string getStatus = 'status';

    public const string getRefundedAt = 'refundedAt';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getRefundNo => 'required',
            self::getOrderId => 'required',
            self::getPaymentId => 'required',
            self::getAmount => 'required',
            self::getReason => 'required',
            self::getStatus => 'required',
            self::getRefundedAt => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRefundNo.'.required' => '请设置退款单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getPaymentId.'.required' => '请设置支付单ID',
            self::getAmount.'.required' => '请设置退款金额',
            self::getReason.'.required' => '请设置退款原因',
            self::getStatus.'.required' => '请设置状态:0=待审核,1=退款中,2=已退款,3=已拒绝',
            self::getRefundedAt.'.required' => '请设置退款完成时间',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
