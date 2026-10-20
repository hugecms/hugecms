<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatMessage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatMessageCreateRequest',
    required: [
        self::getSessionId,
        self::getSenderType,
        self::getSenderId,
        self::getType,
        self::getContent,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getSessionId, description: '会话ID', type: 'integer'),
        new OA\Property(property: self::getSenderType, description: '发送者类型:user=用户,admin=客服,bot=机器人,system=系统', type: 'string'),
        new OA\Property(property: self::getSenderId, description: '发送者ID,system为0', type: 'integer'),
        new OA\Property(property: self::getType, description: '消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件', type: 'string'),
        new OA\Property(property: self::getContent, description: '消息内容,文本或卡片数据(JSON)', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间,消息只追加不修改', type: 'string'),
    ]
)]
class ChatMessageCreateRequest extends FormRequest
{
    public const string getSessionId = 'sessionId';

    public const string getSenderType = 'senderType';

    public const string getSenderId = 'senderId';

    public const string getType = 'type';

    public const string getContent = 'content';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getSessionId => 'required',
            self::getSenderType => 'required',
            self::getSenderId => 'required',
            self::getType => 'required',
            self::getContent => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getSessionId.'.required' => '请设置会话ID',
            self::getSenderType.'.required' => '请设置发送者类型:user=用户,admin=客服,bot=机器人,system=系统',
            self::getSenderId.'.required' => '请设置发送者ID,system为0',
            self::getType.'.required' => '请设置消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件',
            self::getContent.'.required' => '请设置消息内容,文本或卡片数据(JSON)',
            self::getCreateTime.'.required' => '请设置创建时间,消息只追加不修改',
        ];
    }
}
