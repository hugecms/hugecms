<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserBrowsingHistory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBrowsingHistoryUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getTargetType,
        self::getTargetId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '最近浏览时间,重复浏览更新', type: 'string'),
    ]
)]
class UserBrowsingHistoryUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getTargetType.'.required' => '请设置目标类型:article,product',
            self::getTargetId.'.required' => '请设置目标ID',
            self::getCreateTime.'.required' => '请设置最近浏览时间,重复浏览更新',
        ];
    }
}
