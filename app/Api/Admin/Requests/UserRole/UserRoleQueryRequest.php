<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRoleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRoleId, description: '角色ID', type: 'integer'),
    ]
)]
class UserRoleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRoleId = 'roleId';

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
