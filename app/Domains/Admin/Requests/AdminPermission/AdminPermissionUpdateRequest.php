<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminPermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminPermissionUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getName,
        self::getCode,
        self::getType,
        self::getPath,
        self::getIcon,
        self::getSort,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getName, description: '权限名称', type: 'string'),
        new OA\Property(property: self::getCode, description: '权限标识,如 user:list', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:1=菜单,2=按钮', type: 'integer'),
        new OA\Property(property: self::getPath, description: '路由地址', type: 'string'),
        new OA\Property(property: self::getIcon, description: '菜单图标', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class AdminPermissionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getCode = 'code';

    public const string getType = 'type';

    public const string getPath = 'path';

    public const string getIcon = 'icon';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getName => 'required',
            self::getCode => 'required',
            self::getType => 'required',
            self::getPath => 'required',
            self::getIcon => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父级ID,0=顶级',
            self::getName.'.required' => '请设置权限名称',
            self::getCode.'.required' => '请设置权限标识,如 user:list',
            self::getType.'.required' => '请设置类型:1=菜单,2=按钮',
            self::getPath.'.required' => '请设置路由地址',
            self::getIcon.'.required' => '请设置菜单图标',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
