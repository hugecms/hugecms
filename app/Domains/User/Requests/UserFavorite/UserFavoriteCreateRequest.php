<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserFavorite;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFavoriteCreateRequest',
    required: [
        self::getUserId,
        self::getTargetType,
        self::getTargetId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article=文章,product=商品', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserFavoriteCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getTargetType.'.required' => '请设置目标类型:article=文章,product=商品',
            self::getTargetId.'.required' => '请设置目标ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
