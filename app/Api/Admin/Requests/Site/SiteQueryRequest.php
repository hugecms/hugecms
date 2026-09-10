<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSiteCode, description: '站点代码（子域名或标识）', type: 'string'),
        new OA\Property(property: self::getDomain, description: '主域名（如：www.example.com）', type: 'string'),
    ]
)]
class SiteQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSiteCode = 'siteCode';

    public const string getDomain = 'domain';

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
