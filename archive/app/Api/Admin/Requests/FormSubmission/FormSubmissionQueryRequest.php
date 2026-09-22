<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\FormSubmission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormSubmissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFormId, description: '关联表单模板ID', type: 'integer'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class FormSubmissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFormId = 'formId';

    public const string getCreatedAt = 'createdAt';

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
