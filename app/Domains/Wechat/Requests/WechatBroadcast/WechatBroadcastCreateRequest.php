<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatBroadcast;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatBroadcastCreateRequest',
    required: [
        self::getAppid,
        self::getType,
        self::getContent,
        self::getTarget,
        self::getSendCount,
        self::getStatus,
        self::getMsgId,
        self::getUserType,
        self::getUserId,
        self::getSentAt,
    ],
    properties: [
        new OA\Property(property: self::getAppid, description: '群发公众号AppID', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:news=图文,text=文本,image=图片', type: 'string'),
        new OA\Property(property: self::getContent, description: '群发内容(JSON),图文为素材列表', type: 'string'),
        new OA\Property(property: self::getTarget, description: '目标:all=全部粉丝;或标签ID列表', type: 'string'),
        new OA\Property(property: self::getSendCount, description: '送达数', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待发送,1=已发送,2=发送失败', type: 'integer'),
        new OA\Property(property: self::getMsgId, description: '微信群发消息ID,回拉发送结果用', type: 'string'),
        new OA\Property(property: self::getUserType, description: '操作账户类型:admin=后台,system=系统', type: 'string'),
        new OA\Property(property: self::getUserId, description: '操作人ID', type: 'integer'),
        new OA\Property(property: self::getSentAt, description: '发送时间', type: 'string'),
    ]
)]
class WechatBroadcastCreateRequest extends FormRequest
{
    public const string getAppid = 'appid';

    public const string getType = 'type';

    public const string getContent = 'content';

    public const string getTarget = 'target';

    public const string getSendCount = 'sendCount';

    public const string getStatus = 'status';

    public const string getMsgId = 'msgId';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public const string getSentAt = 'sentAt';

    public function rules(): array
    {
        return [
            self::getAppid => 'required',
            self::getType => 'required',
            self::getContent => 'required',
            self::getTarget => 'required',
            self::getSendCount => 'required',
            self::getStatus => 'required',
            self::getMsgId => 'required',
            self::getUserType => 'required',
            self::getUserId => 'required',
            self::getSentAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAppid.'.required' => '请设置群发公众号AppID',
            self::getType.'.required' => '请设置类型:news=图文,text=文本,image=图片',
            self::getContent.'.required' => '请设置群发内容(JSON),图文为素材列表',
            self::getTarget.'.required' => '请设置目标:all=全部粉丝;或标签ID列表',
            self::getSendCount.'.required' => '请设置送达数',
            self::getStatus.'.required' => '请设置状态:0=待发送,1=已发送,2=发送失败',
            self::getMsgId.'.required' => '请设置微信群发消息ID,回拉发送结果用',
            self::getUserType.'.required' => '请设置操作账户类型:admin=后台,system=系统',
            self::getUserId.'.required' => '请设置操作人ID',
            self::getSentAt.'.required' => '请设置发送时间',
        ];
    }
}
