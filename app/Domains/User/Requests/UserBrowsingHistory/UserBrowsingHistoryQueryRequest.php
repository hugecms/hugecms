<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserBrowsingHistory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBrowsingHistoryQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '目标类型:article,product', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '最近浏览时间,重复浏览更新', type: 'string'),
    ]
)]
class UserBrowsingHistoryQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getCreateTime = 'createTime';

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
