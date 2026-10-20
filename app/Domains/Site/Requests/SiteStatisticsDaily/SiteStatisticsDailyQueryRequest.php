<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteStatisticsDaily;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteStatisticsDailyQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStatDate, description: '统计日期', type: 'string'),
    ]
)]
class SiteStatisticsDailyQueryRequest extends FormRequest
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
