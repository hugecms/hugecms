<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemOperationLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemOperationLogUpdateRequest',
    required: [
        self::getId,
        self::getUserType,
        self::getUserId,
        self::getUsername,
        self::getTitle,
        self::getController,
        self::getAction,
        self::getMethod,
        self::getUrl,
        self::getParams,
        self::getStatus,
        self::getCostTime,
        self::getIp,
        self::getUserAgent,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserType, description: '账户类型:admin=后台,user=前台', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getUsername, description: '操作人账号快照', type: 'string'),
        new OA\Property(property: self::getTitle, description: '操作名称,如:删除用户', type: 'string'),
        new OA\Property(property: self::getController, description: '控制器', type: 'string'),
        new OA\Property(property: self::getAction, description: '方法', type: 'string'),
        new OA\Property(property: self::getMethod, description: '请求方式', type: 'string'),
        new OA\Property(property: self::getUrl, description: '请求地址', type: 'string'),
        new OA\Property(property: self::getParams, description: '请求参数(JSON)', type: 'string'),
        new OA\Property(property: self::getStatus, description: '结果:0=失败,1=成功', type: 'integer'),
        new OA\Property(property: self::getCostTime, description: '耗时(毫秒)', type: 'integer'),
        new OA\Property(property: self::getIp, description: '操作IP', type: 'string'),
        new OA\Property(property: self::getUserAgent, description: 'User-Agent', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemOperationLogUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public const string getUsername = 'username';

    public const string getTitle = 'title';

    public const string getController = 'controller';

    public const string getAction = 'action';

    public const string getMethod = 'method';

    public const string getUrl = 'url';

    public const string getParams = 'params';

    public const string getStatus = 'status';

    public const string getCostTime = 'costTime';

    public const string getIp = 'ip';

    public const string getUserAgent = 'userAgent';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserType => 'required',
            self::getUserId => 'required',
            self::getUsername => 'required',
            self::getTitle => 'required',
            self::getController => 'required',
            self::getAction => 'required',
            self::getMethod => 'required',
            self::getUrl => 'required',
            self::getParams => 'required',
            self::getStatus => 'required',
            self::getCostTime => 'required',
            self::getIp => 'required',
            self::getUserAgent => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserType.'.required' => '请设置账户类型:admin=后台,user=前台',
            self::getUserId.'.required' => '请设置用户ID',
            self::getUsername.'.required' => '请设置操作人账号快照',
            self::getTitle.'.required' => '请设置操作名称,如:删除用户',
            self::getController.'.required' => '请设置控制器',
            self::getAction.'.required' => '请设置方法',
            self::getMethod.'.required' => '请设置请求方式',
            self::getUrl.'.required' => '请设置请求地址',
            self::getParams.'.required' => '请设置请求参数(JSON)',
            self::getStatus.'.required' => '请设置结果:0=失败,1=成功',
            self::getCostTime.'.required' => '请设置耗时(毫秒)',
            self::getIp.'.required' => '请设置操作IP',
            self::getUserAgent.'.required' => '请设置User-Agent',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
