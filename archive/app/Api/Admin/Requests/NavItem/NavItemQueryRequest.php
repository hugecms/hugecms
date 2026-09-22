<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\NavItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NavItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID（0代表顶级）', type: 'integer'),
    ]
)]
class NavItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

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
