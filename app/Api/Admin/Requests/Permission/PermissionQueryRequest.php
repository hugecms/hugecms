<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PermissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级权限ID（0表示顶级）', type: 'integer'),
        new OA\Property(property: self::getCode, description: '权限代码（如：content:article:edit）', type: 'string'),
    ]
)]
class PermissionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getCode = 'code';

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
