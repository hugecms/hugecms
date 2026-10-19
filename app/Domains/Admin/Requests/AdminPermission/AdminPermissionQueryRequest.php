<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminPermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminPermissionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getCode, description: '权限标识,如 user:list', type: 'string'),
    ]
)]
class AdminPermissionQueryRequest extends FormRequest
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
