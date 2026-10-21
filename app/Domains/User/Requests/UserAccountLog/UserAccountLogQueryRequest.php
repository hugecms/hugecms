<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccountLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getBizNo, description: '关联业务单号,如提现单号/佣金记录号/充值单号', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserAccountLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getBizNo = 'bizNo';

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
