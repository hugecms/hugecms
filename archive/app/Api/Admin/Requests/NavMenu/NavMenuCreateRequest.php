<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\NavMenu;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'NavMenuCreateRequest',
    required: [
        self::getName,
        self::getAlias,
        self::getDescription,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '菜单名称（如：主导航）', type: 'string'),
        new OA\Property(property: self::getAlias, description: '菜单标识（如：main_nav）', type: 'string'),
        new OA\Property(property: self::getDescription, description: '描述', type: 'string'),
    ]
)]
class NavMenuCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getAlias = 'alias';

    public const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getAlias => 'required',
            self::getDescription => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置菜单名称（如：主导航）',
            self::getAlias.'.required' => '请设置菜单标识（如：main_nav）',
            self::getDescription.'.required' => '请设置描述',
        ];
    }
}
