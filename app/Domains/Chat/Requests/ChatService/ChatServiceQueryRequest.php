<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatService;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatServiceQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAdminUserId, description: '客服管理员ID,关联admin_users', type: 'integer'),
    ]
)]
class ChatServiceQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAdminUserId = 'adminUserId';

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
