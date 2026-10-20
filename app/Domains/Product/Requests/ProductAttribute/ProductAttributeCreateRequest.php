<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductAttribute;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductAttributeCreateRequest',
    required: [
        self::getTypeId,
        self::getName,
        self::getInputType,
        self::getValues,
        self::getIsSpec,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getTypeId, description: '所属商品类型ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '属性名,如:颜色', type: 'string'),
        new OA\Property(property: self::getInputType, description: '录入方式:1=手工输入,2=列表选择', type: 'integer'),
        new OA\Property(property: self::getValues, description: '可选值列表(逗号分隔),列表选择时用', type: 'string'),
        new OA\Property(property: self::getIsSpec, description: '是否规格属性:0=否,1=是(参与SKU组合)', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductAttributeCreateRequest extends FormRequest
{
    public const string getTypeId = 'typeId';

    public const string getName = 'name';

    public const string getInputType = 'inputType';

    public const string getValues = 'values';

    public const string getIsSpec = 'isSpec';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getTypeId => 'required',
            self::getName => 'required',
            self::getInputType => 'required',
            self::getValues => 'required',
            self::getIsSpec => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTypeId.'.required' => '请设置所属商品类型ID',
            self::getName.'.required' => '请设置属性名,如:颜色',
            self::getInputType.'.required' => '请设置录入方式:1=手工输入,2=列表选择',
            self::getValues.'.required' => '请设置可选值列表(逗号分隔),列表选择时用',
            self::getIsSpec.'.required' => '请设置是否规格属性:0=否,1=是(参与SKU组合)',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
