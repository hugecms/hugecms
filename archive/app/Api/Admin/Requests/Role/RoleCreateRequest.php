<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RoleCreateRequest',
    required: [
        self::getName,
        self::getAlias,
        self::getIsSystem,
        self::getDescription,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '角色名称（如：主编、运营）', type: 'string'),
        new OA\Property(property: self::getAlias, description: '角色标识（如：chief_editor）', type: 'string'),
        new OA\Property(property: self::getIsSystem, description: '是否系统内置（不可删除）：1是，0否', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '角色描述', type: 'string'),
    ]
)]
class RoleCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getAlias = 'alias';

    public const string getIsSystem = 'isSystem';

    public const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getAlias => 'required',
            self::getIsSystem => 'required',
            self::getDescription => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置角色名称（如：主编、运营）',
            self::getAlias.'.required' => '请设置角色标识（如：chief_editor）',
            self::getIsSystem.'.required' => '请设置是否系统内置（不可删除）：1是，0否',
            self::getDescription.'.required' => '请设置角色描述',
        ];
    }
}
