<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ModelField;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ModelFieldQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFieldName, description: '字段业务英文名（如：salary）', type: 'string'),
        new OA\Property(property: self::getColumnName, description: '物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）', type: 'string'),
    ]
)]
class ModelFieldQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFieldName = 'fieldName';

    public const string getColumnName = 'columnName';

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
