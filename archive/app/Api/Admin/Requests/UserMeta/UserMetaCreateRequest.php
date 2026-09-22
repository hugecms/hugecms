<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\UserMeta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserMetaCreateRequest',
    required: [
        self::getUserId,
        self::getMetaKey,
        self::getMetaValue,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '关联用户ID', type: 'integer'),
        new OA\Property(property: self::getMetaKey, description: '元数据键名', type: 'string'),
        new OA\Property(property: self::getMetaValue, description: '元数据值（JSON或序列化数据）', type: 'string'),
    ]
)]
class UserMetaCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getMetaKey = 'metaKey';

    public const string getMetaValue = 'metaValue';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getMetaKey => 'required',
            self::getMetaValue => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置关联用户ID',
            self::getMetaKey.'.required' => '请设置元数据键名',
            self::getMetaValue.'.required' => '请设置元数据值（JSON或序列化数据）',
        ];
    }
}
