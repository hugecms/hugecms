<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatSession;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatSessionCreateRequest',
    required: [
        self::getUserId,
        self::getServerId,
        self::getSourceType,
        self::getSourceId,
        self::getStatus,
        self::getUserLastReadId,
        self::getServerLastReadId,
        self::getRating,
        self::getRatedAt,
        self::getAssignedAt,
        self::getClosedAt,
        self::getCloseReason,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '发起用户ID', type: 'integer'),
        new OA\Property(property: self::getServerId, description: '接待客服管理员ID,0=未分配(机器人/排队)', type: 'integer'),
        new OA\Property(property: self::getSourceType, description: '咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询', type: 'string'),
        new OA\Property(property: self::getSourceId, description: '来源ID,商品ID或订单ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=进行中,2=已结束', type: 'integer'),
        new OA\Property(property: self::getUserLastReadId, description: '用户已读消息水位(消息ID),会话级已读', type: 'integer'),
        new OA\Property(property: self::getServerLastReadId, description: '客服已读消息水位(消息ID)', type: 'integer'),
        new OA\Property(property: self::getRating, description: '满意度评分:0=未评,1=1星,2=2星,3=3星,4=4星,5=5星', type: 'integer'),
        new OA\Property(property: self::getRatedAt, description: '评分时间', type: 'string'),
        new OA\Property(property: self::getAssignedAt, description: '分配客服时间', type: 'string'),
        new OA\Property(property: self::getClosedAt, description: '结束时间', type: 'string'),
        new OA\Property(property: self::getCloseReason, description: '结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭', type: 'string'),
    ]
)]
class ChatSessionCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getServerId = 'serverId';

    public const string getSourceType = 'sourceType';

    public const string getSourceId = 'sourceId';

    public const string getStatus = 'status';

    public const string getUserLastReadId = 'userLastReadId';

    public const string getServerLastReadId = 'serverLastReadId';

    public const string getRating = 'rating';

    public const string getRatedAt = 'ratedAt';

    public const string getAssignedAt = 'assignedAt';

    public const string getClosedAt = 'closedAt';

    public const string getCloseReason = 'closeReason';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getServerId => 'required',
            self::getSourceType => 'required',
            self::getSourceId => 'required',
            self::getStatus => 'required',
            self::getUserLastReadId => 'required',
            self::getServerLastReadId => 'required',
            self::getRating => 'required',
            self::getRatedAt => 'required',
            self::getAssignedAt => 'required',
            self::getClosedAt => 'required',
            self::getCloseReason => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置发起用户ID',
            self::getServerId.'.required' => '请设置接待客服管理员ID,0=未分配(机器人/排队)',
            self::getSourceType.'.required' => '请设置咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询',
            self::getSourceId.'.required' => '请设置来源ID,商品ID或订单ID',
            self::getStatus.'.required' => '请设置状态:1=进行中,2=已结束',
            self::getUserLastReadId.'.required' => '请设置用户已读消息水位(消息ID),会话级已读',
            self::getServerLastReadId.'.required' => '请设置客服已读消息水位(消息ID)',
            self::getRating.'.required' => '请设置满意度评分:0=未评,1=1星,2=2星,3=3星,4=4星,5=5星',
            self::getRatedAt.'.required' => '请设置评分时间',
            self::getAssignedAt.'.required' => '请设置分配客服时间',
            self::getClosedAt.'.required' => '请设置结束时间',
            self::getCloseReason.'.required' => '请设置结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭',
        ];
    }
}
