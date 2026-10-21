<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRecordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '触发用户ID', type: 'integer'),
        new OA\Property(property: self::getRuleId, description: '命中规则ID', type: 'integer'),
    ]
)]
class SystemRiskRecordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getRuleId = 'ruleId';

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
