<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteAdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteAdPositionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '广告位标识,如:home_top', type: 'string'),
    ]
)]
class SiteAdPositionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

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
