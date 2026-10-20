<?php

declare(strict_types=1);

namespace App\Domains\Notification\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NotificationCreateRequest',
    required: [
        self::getType,
        self::getNotifiableType,
        self::getNotifiableId,
        self::getData,
        self::getReadAt,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '通知类型(通知类名)', type: 'string'),
        new OA\Property(property: self::getNotifiableType, description: '通知目标类型,如:App\Models\User', type: 'string'),
        new OA\Property(property: self::getNotifiableId, description: '通知目标ID', type: 'integer'),
        new OA\Property(property: self::getData, description: '通知数据(JSON)', type: 'string'),
        new OA\Property(property: self::getReadAt, description: '已读时间,空=未读', type: 'string'),
    ]
)]
class NotificationCreateRequest extends FormRequest
{
    public const string getType = 'type';

    public const string getNotifiableType = 'notifiableType';

    public const string getNotifiableId = 'notifiableId';

    public const string getData = 'data';

    public const string getReadAt = 'readAt';

    public function rules(): array
    {
        return [
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
            self::getType.'.required' => '请设置通知类型(通知类名)',
            self::getNotifiableType.'.required' => '请设置通知目标类型,如:App\Models\User',
            self::getNotifiableId.'.required' => '请设置通知目标ID',
            self::getData.'.required' => '请设置通知数据(JSON)',
            self::getReadAt.'.required' => '请设置已读时间,空=未读',
        ];
    }
}
