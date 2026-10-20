<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemOperationLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemOperationLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserType, description: '账户类型:admin=后台,user=前台', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemOperationLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

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
