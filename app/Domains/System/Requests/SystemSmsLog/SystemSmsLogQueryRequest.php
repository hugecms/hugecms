<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSmsLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSmsLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPhone, description: '手机号', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=发送中,1=成功,2=失败', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemSmsLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPhone = 'phone';

    public const string getStatus = 'status';

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
