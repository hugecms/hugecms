<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\NavMenu;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NavMenuQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAlias, description: '菜单标识（如：main_nav）', type: 'string'),
    ]
)]
class NavMenuQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAlias = 'alias';

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
