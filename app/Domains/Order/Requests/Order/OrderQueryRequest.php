<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '订单号', type: 'string'),
        new OA\Property(property: self::getRequestToken, description: '下单请求幂等令牌,客户端生成,唯一约束防重复下单', type: 'string'),
        new OA\Property(property: self::getOrderChannel, description: '下单渠道:web=PC网站,h5=移动网页,wechat_miniapp=微信小程序,wechat_official=微信公众号,app=原生应用', type: 'string'),
        new OA\Property(property: self::getAttributedUserId, description: '归因用户ID:分享者/分销员,佣金与ROI归因依据', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '下单用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)', type: 'integer'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class OrderQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderNo = 'orderNo';

    public const string getRequestToken = 'requestToken';

    public const string getOrderChannel = 'orderChannel';

    public const string getAttributedUserId = 'attributedUserId';

    public const string getUserId = 'userId';

    public const string getStatus = 'status';

    public const string getCreatedAt = 'createdAt';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
