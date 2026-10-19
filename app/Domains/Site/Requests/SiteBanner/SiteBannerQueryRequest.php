<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteBanner;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteBannerQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPosition, description: '广告位标识,如:home_top', type: 'string'),
    ]
)]
class SiteBannerQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPosition = 'position';

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
