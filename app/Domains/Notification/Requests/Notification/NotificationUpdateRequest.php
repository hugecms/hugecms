<?php

declare(strict_types=1);

namespace App\Domains\Notification\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NotificationUpdateRequest',
    required: [
        self::getId,
        self::getType,
        self::getNotifiableType,
        self::getNotifiableId,
        self::getData,
        self::getReadAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'string'),
        new OA\Property(property: self::getType, description: '', type: 'string'),
        new OA\Property(property: self::getNotifiableType, description: '', type: 'string'),
        new OA\Property(property: self::getNotifiableId, description: '', type: 'integer'),
        new OA\Property(property: self::getData, description: '', type: 'string'),
        new OA\Property(property: self::getReadAt, description: '', type: 'string'),
    ]
)]
class NotificationUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getType = 'type';

    public const string getNotifiableType = 'notifiableType';

    public const string getNotifiableId = 'notifiableId';

    public const string getData = 'data';

    public const string getReadAt = 'readAt';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getType => 'required',
            self::getNotifiableType => 'required',
            self::getNotifiableId => 'required',
            self::getData => 'required',
            self::getReadAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getType.'.required' => '请设置',
            self::getNotifiableType.'.required' => '请设置',
            self::getNotifiableId.'.required' => '请设置',
            self::getData.'.required' => '请设置',
            self::getReadAt.'.required' => '请设置',
        ];
    }
}
