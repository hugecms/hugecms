<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductType;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductTypeCreateRequest',
    required: [
        self::getName,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '类型名称,如:手机', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductTypeCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置类型名称,如:手机',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
