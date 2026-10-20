<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteSearchKeyword;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteSearchKeywordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '搜索词', type: 'string'),
        new OA\Property(property: self::getSearchCount, description: '搜索次数', type: 'integer'),
    ]
)]
class SiteSearchKeywordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getSearchCount = 'searchCount';

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
