<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdPositionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getCode, description: '广告位代码（如：home_banner，模板调用用）', type: 'string'),
    ]
)]
class AdPositionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

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
