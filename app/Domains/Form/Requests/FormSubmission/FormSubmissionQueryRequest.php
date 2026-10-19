<?php

declare(strict_types=1);

namespace App\Domains\Form\Requests\FormSubmission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormSubmissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '提交用户ID,游客为空', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class FormSubmissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getCreateTime = 'createTime';

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
