<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAddress;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAddressUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getReceiverName,
        self::getReceiverPhone,
        self::getReceiverRegion,
        self::getReceiverProvince,
        self::getReceiverCity,
        self::getReceiverDistrict,
        self::getReceiverProvinceCode,
        self::getReceiverCityCode,
        self::getReceiverDistrictCode,
        self::getReceiverAddress,
        self::getIsDefault,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getReceiverName, description: '收货人', type: 'string'),
        new OA\Property(property: self::getReceiverPhone, description: '收货电话', type: 'string'),
        new OA\Property(property: self::getReceiverRegion, description: '省市区(合并展示用)', type: 'string'),
        new OA\Property(property: self::getReceiverProvince, description: '省', type: 'string'),
        new OA\Property(property: self::getReceiverCity, description: '市', type: 'string'),
        new OA\Property(property: self::getReceiverDistrict, description: '区县', type: 'string'),
        new OA\Property(property: self::getReceiverProvinceCode, description: '省行政区划码,物流面单', type: 'string'),
        new OA\Property(property: self::getReceiverCityCode, description: '市行政区划码', type: 'string'),
        new OA\Property(property: self::getReceiverDistrictCode, description: '区县行政区划码', type: 'string'),
        new OA\Property(property: self::getReceiverAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getIsDefault, description: '默认地址:0=否,1=是', type: 'integer'),
    ]
)]
class UserAddressUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getReceiverName = 'receiverName';

    public const string getReceiverPhone = 'receiverPhone';

    public const string getReceiverRegion = 'receiverRegion';

    public const string getReceiverProvince = 'receiverProvince';

    public const string getReceiverCity = 'receiverCity';

    public const string getReceiverDistrict = 'receiverDistrict';

    public const string getReceiverProvinceCode = 'receiverProvinceCode';

    public const string getReceiverCityCode = 'receiverCityCode';

    public const string getReceiverDistrictCode = 'receiverDistrictCode';

    public const string getReceiverAddress = 'receiverAddress';

    public const string getIsDefault = 'isDefault';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getReceiverName => 'required',
            self::getReceiverPhone => 'required',
            self::getReceiverRegion => 'required',
            self::getReceiverProvince => 'required',
            self::getReceiverCity => 'required',
            self::getReceiverDistrict => 'required',
            self::getReceiverProvinceCode => 'required',
            self::getReceiverCityCode => 'required',
            self::getReceiverDistrictCode => 'required',
            self::getReceiverAddress => 'required',
            self::getIsDefault => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getReceiverName.'.required' => '请设置收货人',
            self::getReceiverPhone.'.required' => '请设置收货电话',
            self::getReceiverRegion.'.required' => '请设置省市区(合并展示用)',
            self::getReceiverProvince.'.required' => '请设置省',
            self::getReceiverCity.'.required' => '请设置市',
            self::getReceiverDistrict.'.required' => '请设置区县',
            self::getReceiverProvinceCode.'.required' => '请设置省行政区划码,物流面单',
            self::getReceiverCityCode.'.required' => '请设置市行政区划码',
            self::getReceiverDistrictCode.'.required' => '请设置区县行政区划码',
            self::getReceiverAddress.'.required' => '请设置详细地址',
            self::getIsDefault.'.required' => '请设置默认地址:0=否,1=是',
        ];
    }
}
