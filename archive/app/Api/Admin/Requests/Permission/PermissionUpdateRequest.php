<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PermissionUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getName,
        self::getCode,
        self::getModule,
        self::getDescription,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级权限ID（0表示顶级）', type: 'integer'),
        new OA\Property(property: self::getName, description: '权限名称（如：文章编辑）', type: 'string'),
        new OA\Property(property: self::getCode, description: '权限代码（如：content:article:edit）', type: 'string'),
        new OA\Property(property: self::getModule, description: '所属模块（分组展示用）', type: 'string'),
        new OA\Property(property: self::getDescription, description: '权限描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
    ]
)]
class PermissionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getCode = 'code';

    public const string getModule = 'module';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getName => 'required',
            self::getCode => 'required',
            self::getModule => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父级权限ID（0表示顶级）',
            self::getName.'.required' => '请设置权限名称（如：文章编辑）',
            self::getCode.'.required' => '请设置权限代码（如：content:article:edit）',
            self::getModule.'.required' => '请设置所属模块（分组展示用）',
            self::getDescription.'.required' => '请设置权限描述',
            self::getSort.'.required' => '请设置排序',
        ];
    }
}
