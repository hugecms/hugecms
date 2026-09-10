<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AuditLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuditLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '操作用户ID', type: 'integer'),
        new OA\Property(property: self::getTargetId, description: '目标ID（可能是数字或UUID）', type: 'string'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间（毫秒精度，只增不改）', type: 'string'),
    ]
)]
class AuditLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getTargetId = 'targetId';

    public const string getCreatedAt = 'createdAt';

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
