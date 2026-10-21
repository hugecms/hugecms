<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRuleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getIp, description: 'IP地址,支持IPv4/IPv6', type: 'string'),
    ]
)]
class SystemRiskRuleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getIp = 'ip';

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
