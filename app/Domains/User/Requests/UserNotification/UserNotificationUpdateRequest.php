<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserNotification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserNotificationUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getType,
        self::getTitle,
        self::getContent,
        self::getParams,
        self::getReadAt,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '接收用户ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型:order,comment,system', type: 'string'),
        new OA\Property(property: self::getTitle, description: '通知标题', type: 'string'),
        new OA\Property(property: self::getContent, description: '通知内容', type: 'string'),
        new OA\Property(property: self::getParams, description: '跳转参数(JSON)', type: 'string'),
        new OA\Property(property: self::getReadAt, description: '已读时间,空=未读', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class UserNotificationUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getType = 'type';

    public const string getTitle = 'title';

    public const string getContent = 'content';

    public const string getParams = 'params';

    public const string getReadAt = 'readAt';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getType => 'required',
            self::getTitle => 'required',
            self::getContent => 'required',
            self::getParams => 'required',
            self::getReadAt => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置接收用户ID',
            self::getType.'.required' => '请设置类型:order,comment,system',
            self::getTitle.'.required' => '请设置通知标题',
            self::getContent.'.required' => '请设置通知内容',
            self::getParams.'.required' => '请设置跳转参数(JSON)',
            self::getReadAt.'.required' => '请设置已读时间,空=未读',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
