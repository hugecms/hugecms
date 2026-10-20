<?php

declare(strict_types=1);

namespace App\Domains\Admin\Requests\AdminRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRoleUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getCode,
        self::getDescription,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '角色名称', type: 'string'),
        new OA\Property(property: self::getCode, description: '角色标识', type: 'string'),
        new OA\Property(property: self::getDescription, description: '角色描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class AdminRoleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getCode = 'code';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getCode => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置角色名称',
            self::getCode.'.required' => '请设置角色标识',
            self::getDescription.'.required' => '请设置角色描述',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
