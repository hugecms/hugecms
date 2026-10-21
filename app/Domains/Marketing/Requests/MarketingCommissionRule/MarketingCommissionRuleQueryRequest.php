<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingCommissionRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingCommissionRuleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getInviterId, description: '上级分销员用户ID,0=无上级', type: 'integer'),
    ]
)]
class MarketingCommissionRuleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getInviterId = 'inviterId';

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
