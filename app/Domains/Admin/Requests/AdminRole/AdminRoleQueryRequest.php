<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRoleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '角色标识', type: 'string'),
    ]
)]
class AdminRoleQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
