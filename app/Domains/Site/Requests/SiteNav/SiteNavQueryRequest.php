<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteNav;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteNavQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPosition, description: '展示位置:header=头部,footer=底部', type: 'string'),
    ]
)]
class SiteNavQueryRequest extends FormRequest
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
