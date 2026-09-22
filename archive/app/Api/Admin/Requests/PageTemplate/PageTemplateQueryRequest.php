<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\PageTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PageTemplateQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getTemplateCode, description: '模板代码（唯一标识）', type: 'string'),
        new OA\Property(property: self::getCategory, description: '类别：page页面/post文章/term分类模板', type: 'string'),
    ]
)]
class PageTemplateQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getTemplateCode = 'templateCode';

    public const string getCategory = 'category';

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
