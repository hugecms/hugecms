<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatMessage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatMessageQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSessionId, description: '会话ID', type: 'integer'),
    ]
)]
class ChatMessageQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSessionId = 'sessionId';

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
