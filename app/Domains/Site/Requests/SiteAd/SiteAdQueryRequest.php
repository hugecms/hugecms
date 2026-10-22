<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteAd;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteAdQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPositionCode, description: '广告位标识,关联site_ad_positions.code,如:home_top', type: 'string'),
    ]
)]
class SiteAdQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPositionCode = 'positionCode';

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
