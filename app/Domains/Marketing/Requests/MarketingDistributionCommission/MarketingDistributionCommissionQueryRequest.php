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
        new OA\Property(property: self::getStatus, description: '状态:0=待结算,1=已结算,2=已失效(退款)', type: 'integer'),
    ]
)]
class MarketingDistributionCommissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getDistributorId = 'distributorId';

    public const string getOrderId = 'orderId';

    public const string getStatus = 'status';

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
