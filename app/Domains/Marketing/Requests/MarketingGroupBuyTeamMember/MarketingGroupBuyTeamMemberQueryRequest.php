<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyTeamMemberQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTeamId, description: '团ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '成员用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '成员订单号,关联orders.order_no', type: 'string'),
    ]
)]
class MarketingGroupBuyTeamMemberQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTeamId = 'teamId';

    public const string getUserId = 'userId';

    public const string getOrderNo = 'orderNo';

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
