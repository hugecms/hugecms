<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatService;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatServiceUpdateRequest',
    required: [
        self::getId,
        self::getAdminUserId,
        self::getOnlineStatus,
        self::getMaxSessions,
        self::getCurrentSessions,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAdminUserId, description: '客服管理员ID,关联admin_users', type: 'integer'),
        new OA\Property(property: self::getOnlineStatus, description: '在线状态:0=离线,1=在线,2=忙碌', type: 'integer'),
        new OA\Property(property: self::getMaxSessions, description: '最大接待会话数', type: 'integer'),
        new OA\Property(property: self::getCurrentSessions, description: '当前接待数,分配依据,冗余计数', type: 'integer'),
    ]
)]
class ChatServiceUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAdminUserId = 'adminUserId';

    public const string getOnlineStatus = 'onlineStatus';

    public const string getMaxSessions = 'maxSessions';

    public const string getCurrentSessions = 'currentSessions';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getAdminUserId => 'required',
            self::getOnlineStatus => 'required',
            self::getMaxSessions => 'required',
            self::getCurrentSessions => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getAdminUserId.'.required' => '请设置客服管理员ID,关联admin_users',
            self::getOnlineStatus.'.required' => '请设置在线状态:0=离线,1=在线,2=忙碌',
            self::getMaxSessions.'.required' => '请设置最大接待会话数',
            self::getCurrentSessions.'.required' => '请设置当前接待数,分配依据,冗余计数',
        ];
    }
}
