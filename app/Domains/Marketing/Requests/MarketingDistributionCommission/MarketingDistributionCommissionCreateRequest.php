<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingDistributionCommission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingDistributionCommissionCreateRequest',
    required: [
        self::getDistributorId,
        self::getUserId,
        self::getOrderId,
        self::getOrderItemId,
        self::getLevel,
        self::getOrderAmount,
        self::getRate,
        self::getAmount,
        self::getStatus,
        self::getSettledAt,
    ],
    properties: [
        new OA\Property(property: self::getDistributorId, description: '分销员用户ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '购买用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID,空=整单计佣', type: 'integer'),
        new OA\Property(property: self::getLevel, description: '层级:1=一级,2=二级', type: 'integer'),
        new OA\Property(property: self::getOrderAmount, description: '计佣基数(商品实付)', type: 'string'),
        new OA\Property(property: self::getRate, description: '佣金比例(%),如5.00表示5%', type: 'string'),
        new OA\Property(property: self::getAmount, description: '佣金金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待结算,1=已结算,2=已失效(退款)', type: 'integer'),
        new OA\Property(property: self::getSettledAt, description: '结算时间', type: 'string'),
    ]
)]
class MarketingDistributionCommissionCreateRequest extends FormRequest
{
    public const string getDistributorId = 'distributorId';

    public const string getUserId = 'userId';

    public const string getOrderId = 'orderId';

    public const string getOrderItemId = 'orderItemId';

    public const string getLevel = 'level';

    public const string getOrderAmount = 'orderAmount';

    public const string getRate = 'rate';

    public const string getAmount = 'amount';

    public const string getStatus = 'status';

    public const string getSettledAt = 'settledAt';

    public function rules(): array
    {
        return [
            self::getDistributorId => 'required',
            self::getUserId => 'required',
            self::getOrderId => 'required',
            self::getOrderItemId => 'required',
            self::getLevel => 'required',
            self::getOrderAmount => 'required',
            self::getRate => 'required',
            self::getAmount => 'required',
            self::getStatus => 'required',
            self::getSettledAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getDistributorId.'.required' => '请设置分销员用户ID',
            self::getUserId.'.required' => '请设置购买用户ID',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getOrderItemId.'.required' => '请设置订单明细ID,空=整单计佣',
            self::getLevel.'.required' => '请设置层级:1=一级,2=二级',
            self::getOrderAmount.'.required' => '请设置计佣基数(商品实付)',
            self::getRate.'.required' => '请设置佣金比例(%),如5.00表示5%',
            self::getAmount.'.required' => '请设置佣金金额',
            self::getStatus.'.required' => '请设置状态:0=待结算,1=已结算,2=已失效(退款)',
            self::getSettledAt.'.required' => '请设置结算时间',
        ];
    }
}
