<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatSession;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatSessionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '发起用户ID', type: 'integer'),
        new OA\Property(property: self::getServerId, description: '接待客服管理员ID,0=未分配(机器人/排队)', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=进行中,2=已结束', type: 'integer'),
        new OA\Property(property: self::getUpdatedAt, description: '更新时间', type: 'string'),
    ]
)]
class ChatSessionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getServerId = 'serverId';

    public const string getStatus = 'status';

    public const string getUpdatedAt = 'updatedAt';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
