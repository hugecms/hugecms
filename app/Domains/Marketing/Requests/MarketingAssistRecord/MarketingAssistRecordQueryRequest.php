<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingAssistRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingAssistRecordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '助力活动ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '发起人用户ID', type: 'integer'),
    ]
)]
class MarketingAssistRecordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getUserId = 'userId';

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
