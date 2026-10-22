<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\Order;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderResponse')]
class OrderResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderNo', description: '订单号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'requestToken', description: '下单请求幂等令牌,客户端生成,唯一约束防重复下单', type: 'string')]
    private string $requestToken;

    #[OA\Property(property: 'orderChannel', description: '下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用', type: 'string')]
    private string $orderChannel;

    #[OA\Property(property: 'attributedUserId', description: '归因用户ID:分享者/分销员,佣金与ROI归因依据', type: 'integer')]
    private int $attributedUserId;

    #[OA\Property(property: 'attributionType', description: '归因类型:share=分享,distributor=分销,channel=渠道投放', type: 'string')]
    private string $attributionType;

    #[OA\Property(property: 'userId', description: '下单用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'totalAmount', description: '商品总额', type: 'string')]
    private string $totalAmount;

    #[OA\Property(property: 'freightAmount', description: '运费', type: 'string')]
    private string $freightAmount;

    #[OA\Property(property: 'discountAmount', description: '优惠金额', type: 'string')]
    private string $discountAmount;

    #[OA\Property(property: 'payAmount', description: '实付金额', type: 'string')]
    private string $payAmount;

    #[OA\Property(property: 'status', description: '状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refundStatus', description: '售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写', type: 'integer')]
    private int $refundStatus;

    #[OA\Property(property: 'receiverName', description: '收货人', type: 'string')]
    private string $receiverName;

    #[OA\Property(property: 'receiverPhone', description: '收货电话', type: 'string')]
    private string $receiverPhone;

    #[OA\Property(property: 'receiverRegion', description: '省市区(合并展示用)', type: 'string')]
    private string $receiverRegion;

    #[OA\Property(property: 'receiverProvince', description: '省', type: 'string')]
    private string $receiverProvince;

    #[OA\Property(property: 'receiverCity', description: '市', type: 'string')]
    private string $receiverCity;

    #[OA\Property(property: 'receiverDistrict', description: '区县', type: 'string')]
    private string $receiverDistrict;

    #[OA\Property(property: 'receiverProvinceCode', description: '省行政区划码,物流面单', type: 'string')]
    private string $receiverProvinceCode;

    #[OA\Property(property: 'receiverCityCode', description: '市行政区划码', type: 'string')]
    private string $receiverCityCode;

    #[OA\Property(property: 'receiverDistrictCode', description: '区县行政区划码', type: 'string')]
    private string $receiverDistrictCode;

    #[OA\Property(property: 'receiverAddress', description: '详细地址', type: 'string')]
    private string $receiverAddress;

    #[OA\Property(property: 'buyerRemark', description: '买家备注', type: 'string')]
    private string $buyerRemark;

    #[OA\Property(property: 'sellerRemark', description: '商家备注,客服/发货协作', type: 'string')]
    private string $sellerRemark;

    #[OA\Property(property: 'payTime', description: '支付时间', type: 'string')]
    private string $payTime;

    #[OA\Property(property: 'deliverTime', description: '发货时间', type: 'string')]
    private string $deliverTime;

    #[OA\Property(property: 'finishTime', description: '完成时间', type: 'string')]
    private string $finishTime;

    #[OA\Property(property: 'cancelTime', description: '取消时间', type: 'string')]
    private string $cancelTime;

    #[OA\Property(property: 'cancelReason', description: '取消原因', type: 'string')]
    private string $cancelReason;

    #[OA\Property(property: 'cancelType', description: '取消类型:1=用户取消,2=超时未支付,3=商家/系统取消', type: 'integer')]
    private int $cancelType;

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
     * 获取订单号
     */
    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    /**
     * 设置订单号
     */
    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * 获取下单请求幂等令牌,客户端生成,唯一约束防重复下单
     */
    public function getRequestToken(): string
    {
        return $this->requestToken;
    }

    /**
     * 设置下单请求幂等令牌,客户端生成,唯一约束防重复下单
     */
    public function setRequestToken(string $requestToken): void
    {
        $this->requestToken = $requestToken;
    }

    /**
     * 获取下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
     */
    public function getOrderChannel(): string
    {
        return $this->orderChannel;
    }

    /**
     * 设置下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用
     */
    public function setOrderChannel(string $orderChannel): void
    {
        $this->orderChannel = $orderChannel;
    }

    /**
     * 获取归因用户ID:分享者/分销员,佣金与ROI归因依据
     */
    public function getAttributedUserId(): int
    {
        return $this->attributedUserId;
    }

    /**
     * 设置归因用户ID:分享者/分销员,佣金与ROI归因依据
     */
    public function setAttributedUserId(int $attributedUserId): void
    {
        $this->attributedUserId = $attributedUserId;
    }

    /**
     * 获取归因类型:share=分享,distributor=分销,channel=渠道投放
     */
    public function getAttributionType(): string
    {
        return $this->attributionType;
    }

    /**
     * 设置归因类型:share=分享,distributor=分销,channel=渠道投放
     */
    public function setAttributionType(string $attributionType): void
    {
        $this->attributionType = $attributionType;
    }

    /**
     * 获取下单用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置下单用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取商品总额
     */
    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    /**
     * 设置商品总额
     */
    public function setTotalAmount(string $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * 获取运费
     */
    public function getFreightAmount(): string
    {
        return $this->freightAmount;
    }

    /**
     * 设置运费
     */
    public function setFreightAmount(string $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
    }

    /**
     * 获取优惠金额
     */
    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    /**
     * 设置优惠金额
     */
    public function setDiscountAmount(string $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * 获取实付金额
     */
    public function getPayAmount(): string
    {
        return $this->payAmount;
    }

    /**
     * 设置实付金额
     */
    public function setPayAmount(string $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    /**
     * 获取状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写
     */
    public function getRefundStatus(): int
    {
        return $this->refundStatus;
    }

    /**
     * 设置售后状态:0=无,1=售后中,2=部分退款,3=整单退款;由order_refunds汇总回写
     */
    public function setRefundStatus(int $refundStatus): void
    {
        $this->refundStatus = $refundStatus;
    }

    /**
     * 获取收货人
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * 设置收货人
     */
    public function setReceiverName(string $receiverName): void
    {
        $this->receiverName = $receiverName;
    }

    /**
     * 获取收货电话
     */
    public function getReceiverPhone(): string
    {
        return $this->receiverPhone;
    }

    /**
     * 设置收货电话
     */
    public function setReceiverPhone(string $receiverPhone): void
    {
        $this->receiverPhone = $receiverPhone;
    }

    /**
     * 获取省市区(合并展示用)
     */
    public function getReceiverRegion(): string
    {
        return $this->receiverRegion;
    }

    /**
     * 设置省市区(合并展示用)
     */
    public function setReceiverRegion(string $receiverRegion): void
    {
        $this->receiverRegion = $receiverRegion;
    }

    /**
     * 获取省
     */
    public function getReceiverProvince(): string
    {
        return $this->receiverProvince;
    }

    /**
     * 设置省
     */
    public function setReceiverProvince(string $receiverProvince): void
    {
        $this->receiverProvince = $receiverProvince;
    }

    /**
     * 获取市
     */
    public function getReceiverCity(): string
    {
        return $this->receiverCity;
    }

    /**
     * 设置市
     */
    public function setReceiverCity(string $receiverCity): void
    {
        $this->receiverCity = $receiverCity;
    }

    /**
     * 获取区县
     */
    public function getReceiverDistrict(): string
    {
        return $this->receiverDistrict;
    }

    /**
     * 设置区县
     */
    public function setReceiverDistrict(string $receiverDistrict): void
    {
        $this->receiverDistrict = $receiverDistrict;
    }

    /**
     * 获取省行政区划码,物流面单
     */
    public function getReceiverProvinceCode(): string
    {
        return $this->receiverProvinceCode;
    }

    /**
     * 设置省行政区划码,物流面单
     */
    public function setReceiverProvinceCode(string $receiverProvinceCode): void
    {
        $this->receiverProvinceCode = $receiverProvinceCode;
    }

    /**
     * 获取市行政区划码
     */
    public function getReceiverCityCode(): string
    {
        return $this->receiverCityCode;
    }

    /**
     * 设置市行政区划码
     */
    public function setReceiverCityCode(string $receiverCityCode): void
    {
        $this->receiverCityCode = $receiverCityCode;
    }

    /**
     * 获取区县行政区划码
     */
    public function getReceiverDistrictCode(): string
    {
        return $this->receiverDistrictCode;
    }

    /**
     * 设置区县行政区划码
     */
    public function setReceiverDistrictCode(string $receiverDistrictCode): void
    {
        $this->receiverDistrictCode = $receiverDistrictCode;
    }

    /**
     * 获取详细地址
     */
    public function getReceiverAddress(): string
    {
        return $this->receiverAddress;
    }

    /**
     * 设置详细地址
     */
    public function setReceiverAddress(string $receiverAddress): void
    {
        $this->receiverAddress = $receiverAddress;
    }

    /**
     * 获取买家备注
     */
    public function getBuyerRemark(): string
    {
        return $this->buyerRemark;
    }

    /**
     * 设置买家备注
     */
    public function setBuyerRemark(string $buyerRemark): void
    {
        $this->buyerRemark = $buyerRemark;
    }

    /**
     * 获取商家备注,客服/发货协作
     */
    public function getSellerRemark(): string
    {
        return $this->sellerRemark;
    }

    /**
     * 设置商家备注,客服/发货协作
     */
    public function setSellerRemark(string $sellerRemark): void
    {
        $this->sellerRemark = $sellerRemark;
    }

    /**
     * 获取支付时间
     */
    public function getPayTime(): string
    {
        return $this->payTime;
    }

    /**
     * 设置支付时间
     */
    public function setPayTime(string $payTime): void
    {
        $this->payTime = $payTime;
    }

    /**
     * 获取发货时间
     */
    public function getDeliverTime(): string
    {
        return $this->deliverTime;
    }

    /**
     * 设置发货时间
     */
    public function setDeliverTime(string $deliverTime): void
    {
        $this->deliverTime = $deliverTime;
    }

    /**
     * 获取完成时间
     */
    public function getFinishTime(): string
    {
        return $this->finishTime;
    }

    /**
     * 设置完成时间
     */
    public function setFinishTime(string $finishTime): void
    {
        $this->finishTime = $finishTime;
    }

    /**
     * 获取取消时间
     */
    public function getCancelTime(): string
    {
        return $this->cancelTime;
    }

    /**
     * 设置取消时间
     */
    public function setCancelTime(string $cancelTime): void
    {
        $this->cancelTime = $cancelTime;
    }

    /**
     * 获取取消原因
     */
    public function getCancelReason(): string
    {
        return $this->cancelReason;
    }

    /**
     * 设置取消原因
     */
    public function setCancelReason(string $cancelReason): void
    {
        $this->cancelReason = $cancelReason;
    }

    /**
     * 获取取消类型:1=用户取消,2=超时未支付,3=商家/系统取消
     */
    public function getCancelType(): int
    {
        return $this->cancelType;
    }

    /**
     * 设置取消类型:1=用户取消,2=超时未支付,3=商家/系统取消
     */
    public function setCancelType(int $cancelType): void
    {
        $this->cancelType = $cancelType;
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
