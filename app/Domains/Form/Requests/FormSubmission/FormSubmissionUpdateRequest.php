<?php

declare(strict_types=1);

namespace App\Domains\Form\Requests\FormSubmission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormSubmissionUpdateRequest',
    required: [
        self::getId,
        self::getFormId,
        self::getUserId,
        self::getData,
        self::getStatus,
        self::getIp,
        self::getUserAgent,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFormId, description: '表单ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '提交用户ID,游客为空', type: 'integer'),
        new OA\Property(property: self::getData, description: '提交数据(JSON,{字段标识:值})', type: 'string'),
        new OA\Property(property: self::getStatus, description: '处理状态:0=未处理,1=已处理', type: 'integer'),
        new OA\Property(property: self::getIp, description: '提交IP', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: 'User-Agent', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class FormSubmissionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFormId = 'formId';

    public const string getUserId = 'userId';

    public const string getData = 'data';

    public const string getStatus = 'status';

    public const string getIp = 'ip';

    public const string getUserAgent = 'userAgent';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getFormId => 'required',
            self::getUserId => 'required',
            self::getData => 'required',
            self::getStatus => 'required',
            self::getIp => 'required',
            self::getUserAgent => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getFormId.'.required' => '请设置表单ID',
            self::getUserId.'.required' => '请设置提交用户ID,游客为空',
            self::getData.'.required' => '请设置提交数据(JSON,{字段标识:值})',
            self::getStatus.'.required' => '请设置处理状态:0=未处理,1=已处理',
            self::getIp.'.required' => '请设置提交IP',
            self::getUserAgent.'.required' => '请设置User-Agent',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
