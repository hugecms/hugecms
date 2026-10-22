<?php

declare(strict_types=1);

namespace App\Domains\Notification\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NotificationQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'string'),
        new OA\Property(property: self::getNotifiableType, description: '', type: 'string'),
        new OA\Property(property: self::getNotifiableId, description: '', type: 'integer'),
    ]
)]
class NotificationQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getNotifiableType = 'notifiableType';

    public const string getNotifiableId = 'notifiableId';

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
