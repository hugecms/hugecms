<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\FormSubmission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormSubmissionUpdateRequest',
    required: [
        self::getId,
        self::getFormId,
        self::getSubmissionData,
        self::getSubmitterIp,
        self::getUserAgent,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFormId, description: '关联表单模板ID', type: 'integer'),
        new OA\Property(property: self::getSubmissionData, description: '用户提交的具体表单数据（JSON）', type: 'string'),
        new OA\Property(property: self::getSubmitterIp, description: '提交者IP', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: '提交者UA', type: 'string'),
    ]
)]
class FormSubmissionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFormId = 'formId';

    public const string getSubmissionData = 'submissionData';

    public const string getSubmitterIp = 'submitterIp';

    public const string getUserAgent = 'userAgent';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getFormId => 'required',
            self::getSubmissionData => 'required',
            self::getSubmitterIp => 'required',
            self::getUserAgent => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getFormId.'.required' => '请设置关联表单模板ID',
            self::getSubmissionData.'.required' => '请设置用户提交的具体表单数据（JSON）',
            self::getSubmitterIp.'.required' => '请设置提交者IP',
            self::getUserAgent.'.required' => '请设置提交者UA',
        ];
    }
}
