<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatQuickReply;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatQuickReplyQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGroup, description: '分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流', type: 'string'),
    ]
)]
class ChatQuickReplyQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getGroup = 'group';

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
