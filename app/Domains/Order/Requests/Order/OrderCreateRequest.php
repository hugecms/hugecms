<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderCreateRequest',
    required: [
        self::getOrderNo,
        self::getRequestToken,
        self::getOrderChannel,
        self::getAttributedUserId,
        self::getAttributionType,
        self::getUserId,
        self::getTotalAmount,
        self::getFreightAmount,
        self::getDiscountAmount,
        self::getPayAmount,
        self::getStatus,
        self::getRefundStatus,
        self::getReceiverName,
        self::getReceiverPhone,
        self::getReceiverRegion,
        self::getReceiverProvince,
        self::getReceiverCity,
        self::getReceiverDistrict,
        self::getReceiverProvinceCode,
        self::getReceiverCityCode,
        self::getReceiverDistrictCode,
        self::getReceiverAddress,
        self::getBuyerRemark,
        self::getSellerRemark,
        self::getPayTime,
        self::getDeliverTime,
        self::getFinishTime,
        self::getCancelTime,
        self::getCancelReason,
        self::getCancelType,
    ],
    properties: [
        new OA\Property(property: self::getOrderNo, description: '订单号', type: 'string'),
        new OA\Property(property: self::getRequestToken, description: '下单请求幂等令牌,客户端生成,唯一约束防重复下单', type: 'string'),
        new OA\Property(property: self::getOrderChannel, description: '下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用', type: 'string'),
        new OA\Property(property: self::getAttributedUserId, description: '归因用户ID:分享者/分销员,佣金与ROI归因依据', type: 'integer'),
        new OA\Property(property: self::getAttributionType, description: '归因类型:share=分享,distributor=分销,channel=渠道投放', type: 'string'),
        new OA\Property(property: self::getUserId, description: '下单用户ID', type: 'integer'),
        new OA\Property(property: self::getTotalAmount, description: '商品总额', type: 'string'),
        new OA\Property(property: self::getFreightAmount, description: '运费', type: 'string'),
        new OA\Property(property: self::getDiscountAmount, description: '优惠金额', type: 'string'),
        new OA\Property(property: self::getPayAmount, description: '实付金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)', type: 'integer'),
        new OA\Property(property: self::getRefundStatus, description: '售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写', type: 'integer'),
        new OA\Property(property: self::getReceiverName, description: '收货人', type: 'string'),
        new OA\Property(property: self::getReceiverPhone, description: '收货电话', type: 'string'),
        new OA\Property(property: self::getReceiverRegion, description: '省市区(合并展示用)', type: 'string'),
        new OA\Property(property: self::getReceiverProvince, description: '省', type: 'string'),
        new OA\Property(property: self::getReceiverCity, description: '市', type: 'string'),
        new OA\Property(property: self::getReceiverDistrict, description: '区县', type: 'string'),
        new OA\Property(property: self::getReceiverProvinceCode, description: '省行政区划码,物流面单', type: 'string'),
        new OA\Property(property: self::getReceiverCityCode, description: '市行政区划码', type: 'string'),
        new OA\Property(property: self::getReceiverDistrictCode, description: '区县行政区划码', type: 'string'),
        new OA\Property(property: self::getReceiverAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getBuyerRemark, description: '买家备注', type: 'string'),
        new OA\Property(property: self::getSellerRemark, description: '商家备注,客服/发货协作', type: 'string'),
        new OA\Property(property: self::getPayTime, description: '支付时间', type: 'string'),
        new OA\Property(property: self::getDeliverTime, description: '发货时间', type: 'string'),
        new OA\Property(property: self::getFinishTime, description: '完成时间', type: 'string'),
        new OA\Property(property: self::getCancelTime, description: '取消时间', type: 'string'),
        new OA\Property(property: self::getCancelReason, description: '取消原因', type: 'string'),
        new OA\Property(property: self::getCancelType, description: '取消类型:1=用户取消,2=超时未支付,3=商家/系统取消', type: 'integer'),
    ]
)]
class OrderCreateRequest extends FormRequest
{
    public const string getOrderNo = 'orderNo';

    public const string getRequestToken = 'requestToken';

    public const string getOrderChannel = 'orderChannel';

    public const string getAttributedUserId = 'attributedUserId';

    public const string getAttributionType = 'attributionType';

    public const string getUserId = 'userId';

    public const string getTotalAmount = 'totalAmount';

    public const string getFreightAmount = 'freightAmount';

    public const string getDiscountAmount = 'discountAmount';

    public const string getPayAmount = 'payAmount';

    public const string getStatus = 'status';

    public const string getRefundStatus = 'refundStatus';

    public const string getReceiverName = 'receiverName';

    public const string getReceiverPhone = 'receiverPhone';

    public const string getReceiverRegion = 'receiverRegion';

    public const string getReceiverProvince = 'receiverProvince';

    public const string getReceiverCity = 'receiverCity';

    public const string getReceiverDistrict = 'receiverDistrict';

    public const string getReceiverProvinceCode = 'receiverProvinceCode';

    public const string getReceiverCityCode = 'receiverCityCode';

    public const string getReceiverDistrictCode = 'receiverDistrictCode';

    public const string getReceiverAddress = 'receiverAddress';

    public const string getBuyerRemark = 'buyerRemark';

    public const string getSellerRemark = 'sellerRemark';

    public const string getPayTime = 'payTime';

    public const string getDeliverTime = 'deliverTime';

    public const string getFinishTime = 'finishTime';

    public const string getCancelTime = 'cancelTime';

    public const string getCancelReason = 'cancelReason';

    public const string getCancelType = 'cancelType';

    public function rules(): array
    {
        return [
            self::getOrderNo => 'required',
            self::getRequestToken => 'required',
            self::getOrderChannel => 'required',
            self::getAttributedUserId => 'required',
            self::getAttributionType => 'required',
            self::getUserId => 'required',
            self::getTotalAmount => 'required',
            self::getFreightAmount => 'required',
            self::getDiscountAmount => 'required',
            self::getPayAmount => 'required',
            self::getStatus => 'required',
            self::getRefundStatus => 'required',
            self::getReceiverName => 'required',
            self::getReceiverPhone => 'required',
            self::getReceiverRegion => 'required',
            self::getReceiverProvince => 'required',
            self::getReceiverCity => 'required',
            self::getReceiverDistrict => 'required',
            self::getReceiverProvinceCode => 'required',
            self::getReceiverCityCode => 'required',
            self::getReceiverDistrictCode => 'required',
            self::getReceiverAddress => 'required',
            self::getBuyerRemark => 'required',
            self::getSellerRemark => 'required',
            self::getPayTime => 'required',
            self::getDeliverTime => 'required',
            self::getFinishTime => 'required',
            self::getCancelTime => 'required',
            self::getCancelReason => 'required',
            self::getCancelType => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderNo.'.required' => '请设置订单号',
            self::getRequestToken.'.required' => '请设置下单请求幂等令牌,客户端生成,唯一约束防重复下单',
            self::getOrderChannel.'.required' => '请设置下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用',
            self::getAttributedUserId.'.required' => '请设置归因用户ID:分享者/分销员,佣金与ROI归因依据',
            self::getAttributionType.'.required' => '请设置归因类型:share=分享,distributor=分销,channel=渠道投放',
            self::getUserId.'.required' => '请设置下单用户ID',
            self::getTotalAmount.'.required' => '请设置商品总额',
            self::getFreightAmount.'.required' => '请设置运费',
            self::getDiscountAmount.'.required' => '请设置优惠金额',
            self::getPayAmount.'.required' => '请设置实付金额',
            self::getStatus.'.required' => '请设置状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)',
            self::getRefundStatus.'.required' => '请设置售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写',
            self::getReceiverName.'.required' => '请设置收货人',
            self::getReceiverPhone.'.required' => '请设置收货电话',
            self::getReceiverRegion.'.required' => '请设置省市区(合并展示用)',
            self::getReceiverProvince.'.required' => '请设置省',
            self::getReceiverCity.'.required' => '请设置市',
            self::getReceiverDistrict.'.required' => '请设置区县',
            self::getReceiverProvinceCode.'.required' => '请设置省行政区划码,物流面单',
            self::getReceiverCityCode.'.required' => '请设置市行政区划码',
            self::getReceiverDistrictCode.'.required' => '请设置区县行政区划码',
            self::getReceiverAddress.'.required' => '请设置详细地址',
            self::getBuyerRemark.'.required' => '请设置买家备注',
            self::getSellerRemark.'.required' => '请设置商家备注,客服/发货协作',
            self::getPayTime.'.required' => '请设置支付时间',
            self::getDeliverTime.'.required' => '请设置发货时间',
            self::getFinishTime.'.required' => '请设置完成时间',
            self::getCancelTime.'.required' => '请设置取消时间',
            self::getCancelReason.'.required' => '请设置取消原因',
            self::getCancelType.'.required' => '请设置取消类型:1=用户取消,2=超时未支付,3=商家/系统取消',
        ];
    }
}
