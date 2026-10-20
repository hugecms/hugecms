<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductType;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductTypeUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '类型名称,如:手机', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductTypeUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置类型名称,如:手机',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
