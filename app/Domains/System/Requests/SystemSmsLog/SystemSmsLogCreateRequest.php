<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSmsLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSmsLogCreateRequest',
    required: [
        self::getPhone,
        self::getTemplate,
        self::getContent,
        self::getParams,
        self::getGateway,
        self::getStatus,
        self::getMessageId,
        self::getError,
        self::getCostTime,
        self::getIp,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getTemplate, description: '模板标识,如:verify_code', type: 'string'),
        new OA\Property(property: self::getContent, description: '短信内容', type: 'string'),
        new OA\Property(property: self::getParams, description: '模板变量(JSON)', type: 'string'),
        new OA\Property(property: self::getGateway, description: '发送网关:aliyun,tencent,qiniu等', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=发送中,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: self::getMessageId, description: '网关消息ID', type: 'string'),
        new OA\Property(property: self::getError, description: '失败原因', type: 'string'),
        new OA\Property(property: self::getCostTime, description: '耗时(毫秒)', type: 'integer'),
        new OA\Property(property: self::getIp, description: '触发来源IP', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemSmsLogCreateRequest extends FormRequest
{
    public const string getPhone = 'phone';

    public const string getTemplate = 'template';

    public const string getContent = 'content';

    public const string getParams = 'params';

    public const string getGateway = 'gateway';

    public const string getStatus = 'status';

    public const string getMessageId = 'messageId';

    public const string getError = 'error';

    public const string getCostTime = 'costTime';

    public const string getIp = 'ip';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getPhone => 'required',
            self::getTemplate => 'required',
            self::getContent => 'required',
            self::getParams => 'required',
            self::getGateway => 'required',
            self::getStatus => 'required',
            self::getMessageId => 'required',
            self::getError => 'required',
            self::getCostTime => 'required',
            self::getIp => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPhone.'.required' => '请设置手机号',
            self::getTemplate.'.required' => '请设置模板标识,如:verify_code',
            self::getContent.'.required' => '请设置短信内容',
            self::getParams.'.required' => '请设置模板变量(JSON)',
            self::getGateway.'.required' => '请设置发送网关:aliyun,tencent,qiniu等',
            self::getStatus.'.required' => '请设置状态:0=发送中,1=成功,2=失败',
            self::getMessageId.'.required' => '请设置网关消息ID',
            self::getError.'.required' => '请设置失败原因',
            self::getCostTime.'.required' => '请设置耗时(毫秒)',
            self::getIp.'.required' => '请设置触发来源IP',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
