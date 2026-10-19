<?php

declare(strict_types=1);

namespace App\Domains\Form\Requests\FormField;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormFieldQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFieldName, description: '字段标识,英文,提交数据的键', type: 'string'),
    ]
)]
class FormFieldQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFieldName = 'fieldName';

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
