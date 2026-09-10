<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\StatisticsDaily;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StatisticsDailyQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStatDate, description: '统计日期', type: 'string'),
    ]
)]
class StatisticsDailyQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getStatDate = 'statDate';

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
