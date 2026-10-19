<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemLoginLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemLoginLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID,账号不存在时为空', type: 'integer'),
        new OA\Property(property: self::getIp, description: '登录IP', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class SystemLoginLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getIp = 'ip';

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
