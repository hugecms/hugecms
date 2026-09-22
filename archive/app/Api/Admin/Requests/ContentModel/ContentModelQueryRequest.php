<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ContentModel;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentModelQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getAlias, description: '模型别名（代码/URL用，如：recruitment）', type: 'string'),
        new OA\Property(property: self::getTableName, description: '对应物理数据表名（如：data_article；模型创建时由 alias 生成并固化，此后不可变，alias 变更不联动改名）', type: 'string'),
    ]
)]
class ContentModelQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getAlias = 'alias';

    public const string getTableName = 'tableName';

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
