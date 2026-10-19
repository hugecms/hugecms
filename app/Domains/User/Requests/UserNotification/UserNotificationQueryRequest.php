<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserNotification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserNotificationQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getReadAt, description: '已读时间,空=未读', type: 'string'),
    ]
)]
class UserNotificationQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getReadAt = 'readAt';

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
