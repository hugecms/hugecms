<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderUpdateRequest',
    required: [
        self::getId,
        self::getOrderNo,
        self::getUserId,
        self::getTotalAmount,
        self::getFreightAmount,
        self::getDiscountAmount,
        self::getPayAmount,
        self::getStatus,
        self::getReceiverName,
        self::getReceiverPhone,
        self::getReceiverRegion,
        self::getReceiverAddress,
        self::getBuyerRemark,
        self::getPayTime,
        self::getDeliverTime,
        self::getFinishTime,
        self::getCancelTime,
        self::getCancelReason,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '订单号', type: 'string'),
        new OA\Property(property: self::getUserId, description: '下单用户ID', type: 'integer'),
        new OA\Property(property: self::getTotalAmount, description: '商品总额', type: 'string'),
        new OA\Property(property: self::getFreightAmount, description: '运费', type: 'string'),
        new OA\Property(property: self::getDiscountAmount, description: '优惠金额', type: 'string'),
        new OA\Property(property: self::getPayAmount, description: '实付金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消', type: 'integer'),
        new OA\Property(property: self::getReceiverName, description: '收货人', type: 'string'),
        new OA\Property(property: self::getReceiverPhone, description: '收货电话', type: 'string'),
        new OA\Property(property: self::getReceiverRegion, description: '省市区', type: 'string'),
        new OA\Property(property: self::getReceiverAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getBuyerRemark, description: '买家备注', type: 'string'),
        new OA\Property(property: self::getPayTime, description: '支付时间', type: 'string'),
        new OA\Property(property: self::getDeliverTime, description: '发货时间', type: 'string'),
        new OA\Property(property: self::getFinishTime, description: '完成时间', type: 'string'),
        new OA\Property(property: self::getCancelTime, description: '取消时间', type: 'string'),
        new OA\Property(property: self::getCancelReason, description: '取消原因', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class OrderUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderNo = 'orderNo';

    public const string getUserId = 'userId';

    public const string getTotalAmount = 'totalAmount';

    public const string getFreightAmount = 'freightAmount';

    public const string getDiscountAmount = 'discountAmount';

    public const string getPayAmount = 'payAmount';

    public const string getStatus = 'status';

    public const string getReceiverName = 'receiverName';

    public const string getReceiverPhone = 'receiverPhone';

    public const string getReceiverRegion = 'receiverRegion';

    public const string getReceiverAddress = 'receiverAddress';

    public const string getBuyerRemark = 'buyerRemark';

    public const string getPayTime = 'payTime';

    public const string getDeliverTime = 'deliverTime';

    public const string getFinishTime = 'finishTime';

    public const string getCancelTime = 'cancelTime';

    public const string getCancelReason = 'cancelReason';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOrderNo => 'required',
            self::getUserId => 'required',
            self::getTotalAmount => 'required',
            self::getFreightAmount => 'required',
            self::getDiscountAmount => 'required',
            self::getPayAmount => 'required',
            self::getStatus => 'required',
            self::getReceiverName => 'required',
            self::getReceiverPhone => 'required',
            self::getReceiverRegion => 'required',
            self::getReceiverAddress => 'required',
            self::getBuyerRemark => 'required',
            self::getPayTime => 'required',
            self::getDeliverTime => 'required',
            self::getFinishTime => 'required',
            self::getCancelTime => 'required',
            self::getCancelReason => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOrderNo.'.required' => '请设置订单号',
            self::getUserId.'.required' => '请设置下单用户ID',
            self::getTotalAmount.'.required' => '请设置商品总额',
            self::getFreightAmount.'.required' => '请设置运费',
            self::getDiscountAmount.'.required' => '请设置优惠金额',
            self::getPayAmount.'.required' => '请设置实付金额',
            self::getStatus.'.required' => '请设置状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消',
            self::getReceiverName.'.required' => '请设置收货人',
            self::getReceiverPhone.'.required' => '请设置收货电话',
            self::getReceiverRegion.'.required' => '请设置省市区',
            self::getReceiverAddress.'.required' => '请设置详细地址',
            self::getBuyerRemark.'.required' => '请设置买家备注',
            self::getPayTime.'.required' => '请设置支付时间',
            self::getDeliverTime.'.required' => '请设置发货时间',
            self::getFinishTime.'.required' => '请设置完成时间',
            self::getCancelTime.'.required' => '请设置取消时间',
            self::getCancelReason.'.required' => '请设置取消原因',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
