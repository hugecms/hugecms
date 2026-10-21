<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '活动开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '活动结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingPromotionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

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
