<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingDistributionCommission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingDistributionCommissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getDistributorId, description: '分销员用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待结算,1=已结算(负额冲销记录亦为此态),2=已失效(保护期内退款),3=已冲销(结算后退款,原记录侧)', type: 'integer'),
        new OA\Property(property: self::getReversalOfId, description: '原佣金记录ID,NULL=本记录为原始计佣记录,冲销记录回指原记录', type: 'integer'),
    ]
)]
class MarketingDistributionCommissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getDistributorId = 'distributorId';

    public const string getOrderId = 'orderId';

    public const string getStatus = 'status';

    public const string getReversalOfId = 'reversalOfId';

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
