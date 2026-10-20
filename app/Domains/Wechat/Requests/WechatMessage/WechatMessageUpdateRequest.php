<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatMessage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatMessageUpdateRequest',
    required: [
        self::getId,
        self::getAppid,
        self::getUserId,
        self::getTemplateId,
        self::getScene,
        self::getRelatedId,
        self::getOpenid,
        self::getContent,
        self::getStatus,
        self::getError,
        self::getSentAt,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '发送所用应用AppID', type: 'string'),
        new OA\Property(property: self::getUserId, description: '目标用户ID', type: 'integer'),
        new OA\Property(property: self::getTemplateId, description: '模板ID', type: 'integer'),
        new OA\Property(property: self::getScene, description: '业务场景,同模板表scene', type: 'string'),
        new OA\Property(property: self::getRelatedId, description: '关联业务ID,如订单ID', type: 'integer'),
        new OA\Property(property: self::getOpenid, description: '接收者OpenID快照', type: 'string'),
        new OA\Property(property: self::getContent, description: '推送内容快照(JSON)', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待发送,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: self::getError, description: '失败原因,如:用户未关注/订阅次数用尽', type: 'string'),
        new OA\Property(property: self::getSentAt, description: '发送时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class WechatMessageUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getUserId = 'userId';

    public const string getTemplateId = 'templateId';

    public const string getScene = 'scene';

    public const string getRelatedId = 'relatedId';

    public const string getOpenid = 'openid';

    public const string getContent = 'content';

    public const string getStatus = 'status';

    public const string getError = 'error';

    public const string getSentAt = 'sentAt';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getAppid => 'required',
            self::getUserId => 'required',
            self::getTemplateId => 'required',
            self::getScene => 'required',
            self::getRelatedId => 'required',
            self::getOpenid => 'required',
            self::getContent => 'required',
            self::getStatus => 'required',
            self::getError => 'required',
            self::getSentAt => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getAppid.'.required' => '请设置发送所用应用AppID',
            self::getUserId.'.required' => '请设置目标用户ID',
            self::getTemplateId.'.required' => '请设置模板ID',
            self::getScene.'.required' => '请设置业务场景,同模板表scene',
            self::getRelatedId.'.required' => '请设置关联业务ID,如订单ID',
            self::getOpenid.'.required' => '请设置接收者OpenID快照',
            self::getContent.'.required' => '请设置推送内容快照(JSON)',
            self::getStatus.'.required' => '请设置状态:0=待发送,1=成功,2=失败',
            self::getError.'.required' => '请设置失败原因,如:用户未关注/订阅次数用尽',
            self::getSentAt.'.required' => '请设置发送时间',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
