<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Requests\ShippingMethod;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingMethodCreateRequest',
    required: [
        self::getName,
        self::getCode,
        self::getDescription,
        self::getStatus,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '配送方式名称,如:顺丰标快', type: 'string'),
        new OA\Property(property: self::getCode, description: '承运商/运费模板标识', type: 'string'),
        new OA\Property(property: self::getDescription, description: '配送说明', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
    ]
)]
class ShippingMethodCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getCode = 'code';

    public const string getDescription = 'description';

    public const string getStatus = 'status';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getCode => 'required',
            self::getDescription => 'required',
            self::getStatus => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置配送方式名称,如:顺丰标快',
            self::getCode.'.required' => '请设置承运商/运费模板标识',
            self::getDescription.'.required' => '请设置配送说明',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
            self::getSort.'.required' => '请设置排序',
        ];
    }
}
