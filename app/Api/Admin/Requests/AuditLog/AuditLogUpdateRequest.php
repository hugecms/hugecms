<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AuditLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuditLogUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getUserName,
        self::getClientIp,
        self::getUserAgent,
        self::getRequestId,
        self::getEventType,
        self::getTargetType,
        self::getTargetId,
        self::getTargetName,
        self::getOldValue,
        self::getNewValue,
        self::getOperationResult,
        self::getErrorMessage,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '操作用户ID', type: 'integer'),
        new OA\Property(property: self::getUserName, description: '操作用户名（冗余，防用户被删后无记录）', type: 'string'),
        new OA\Property(property: self::getClientIp, description: '客户端IP（支持IPv6）', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: '客户端UA信息', type: 'string'),
        new OA\Property(property: self::getRequestId, description: '请求追踪ID（关联一次请求的所有日志）', type: 'string'),
        new OA\Property(property: self::getEventType, description: '事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT', type: 'string'),
        new OA\Property(property: self::getTargetType, description: '目标类型：content/term/user/attachment/config/comment/form_submission', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID（可能是数字或UUID）', type: 'string'),
        new OA\Property(property: self::getTargetName, description: '目标名称（冗余，便于展示）', type: 'string'),
        new OA\Property(property: self::getOldValue, description: '修改前的数据快照（JSON）', type: 'string'),
        new OA\Property(property: self::getNewValue, description: '修改后的数据快照（JSON）', type: 'string'),
        new OA\Property(property: self::getOperationResult, description: '操作结果：0失败，1成功', type: 'integer'),
        new OA\Property(property: self::getErrorMessage, description: '失败时的错误信息', type: 'string'),
    ]
)]
class AuditLogUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getUserName = 'userName';

    public const string getClientIp = 'clientIp';

    public const string getUserAgent = 'userAgent';

    public const string getRequestId = 'requestId';

    public const string getEventType = 'eventType';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getTargetName = 'targetName';

    public const string getOldValue = 'oldValue';

    public const string getNewValue = 'newValue';

    public const string getOperationResult = 'operationResult';

    public const string getErrorMessage = 'errorMessage';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getUserName => 'required',
            self::getClientIp => 'required',
            self::getUserAgent => 'required',
            self::getRequestId => 'required',
            self::getEventType => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getTargetName => 'required',
            self::getOldValue => 'required',
            self::getNewValue => 'required',
            self::getOperationResult => 'required',
            self::getErrorMessage => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置操作用户ID',
            self::getUserName.'.required' => '请设置操作用户名（冗余，防用户被删后无记录）',
            self::getClientIp.'.required' => '请设置客户端IP（支持IPv6）',
            self::getUserAgent.'.required' => '请设置客户端UA信息',
            self::getRequestId.'.required' => '请设置请求追踪ID（关联一次请求的所有日志）',
            self::getEventType.'.required' => '请设置事件类型：LOGIN/LOGOUT/CREATE/UPDATE/DELETE/PUBLISH/EXPORT',
            self::getTargetType.'.required' => '请设置目标类型：content/term/user/attachment/config/comment/form_submission',
            self::getTargetId.'.required' => '请设置目标ID（可能是数字或UUID）',
            self::getTargetName.'.required' => '请设置目标名称（冗余，便于展示）',
            self::getOldValue.'.required' => '请设置修改前的数据快照（JSON）',
            self::getNewValue.'.required' => '请设置修改后的数据快照（JSON）',
            self::getOperationResult.'.required' => '请设置操作结果：0失败，1成功',
            self::getErrorMessage.'.required' => '请设置失败时的错误信息',
        ];
    }
}
