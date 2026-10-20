<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Requests\ShippingArea;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingAreaUpdateRequest',
    required: [
        self::getId,
        self::getShippingId,
        self::getName,
        self::getFeeConfig,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '区域名称,如:华东地区', type: 'string'),
        new OA\Property(property: self::getFeeConfig, description: '运价配置(JSON):首重/续重/首费/续费/固定运费等', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class ShippingAreaUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShippingId = 'shippingId';

    public const string getName = 'name';

    public const string getFeeConfig = 'feeConfig';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getShippingId => 'required',
            self::getName => 'required',
            self::getFeeConfig => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getShippingId.'.required' => '请设置配送方式ID',
            self::getName.'.required' => '请设置区域名称,如:华东地区',
            self::getFeeConfig.'.required' => '请设置运价配置(JSON):首重/续重/首费/续费/固定运费等',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
