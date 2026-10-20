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
        self::getType,
        self::getAmount,
        self::getReason,
        self::getAdminNote,
        self::getImages,
        self::getReturnExpressCompany,
        self::getReturnExpressNo,
        self::getStatus,
        self::getRefundedAt,
    ],
    properties: [
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getPaymentId, description: '关联支付单ID(payments 表)', type: 'integer'),
        new OA\Property(property: self::getType, description: '售后类型:1=仅退款,2=退货退款', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '退款金额', type: 'string'),
        new OA\Property(property: self::getReason, description: '退款原因', type: 'string'),
        new OA\Property(property: self::getAdminNote, description: '商家审核备注/拒绝原因', type: 'string'),
        new OA\Property(property: self::getImages, description: '申请凭证图片(JSON数组)', type: 'string'),
        new OA\Property(property: self::getReturnExpressCompany, description: '退货物流公司,退货退款时填写', type: 'string'),
        new OA\Property(property: self::getReturnExpressNo, description: '退货物流单号', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审核,1=退款中,2=已退款,3=已拒绝', type: 'integer'),
        new OA\Property(property: self::getRefundedAt, description: '退款完成时间', type: 'string'),
    ]
)]
class OrderRefundCreateRequest extends FormRequest
{
    public const string getRefundNo = 'refundNo';

    public const string getOrderId = 'orderId';

    public const string getPaymentId = 'paymentId';

    public const string getType = 'type';

    public const string getAmount = 'amount';

    public const string getReason = 'reason';

    public const string getAdminNote = 'adminNote';

    public const string getImages = 'images';

    public const string getReturnExpressCompany = 'returnExpressCompany';

    public const string getReturnExpressNo = 'returnExpressNo';

    public const string getStatus = 'status';

    public const string getRefundedAt = 'refundedAt';

    public function rules(): array
    {
        return [
            self::getRefundNo => 'required',
            self::getOrderId => 'required',
            self::getPaymentId => 'required',
            self::getType => 'required',
            self::getAmount => 'required',
            self::getReason => 'required',
            self::getAdminNote => 'required',
            self::getImages => 'required',
            self::getReturnExpressCompany => 'required',
            self::getReturnExpressNo => 'required',
            self::getStatus => 'required',
            self::getRefundedAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRefundNo.'.required' => '请设置退款单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getPaymentId.'.required' => '请设置关联支付单ID(payments 表)',
            self::getType.'.required' => '请设置售后类型:1=仅退款,2=退货退款',
            self::getAmount.'.required' => '请设置退款金额',
            self::getReason.'.required' => '请设置退款原因',
            self::getAdminNote.'.required' => '请设置商家审核备注/拒绝原因',
            self::getImages.'.required' => '请设置申请凭证图片(JSON数组)',
            self::getReturnExpressCompany.'.required' => '请设置退货物流公司,退货退款时填写',
            self::getReturnExpressNo.'.required' => '请设置退货物流单号',
            self::getStatus.'.required' => '请设置状态:0=待审核,1=退款中,2=已退款,3=已拒绝',
            self::getRefundedAt.'.required' => '请设置退款完成时间',
        ];
    }
}
