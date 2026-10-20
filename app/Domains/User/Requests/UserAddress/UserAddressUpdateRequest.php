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
        self::getReceiverAddress,
        self::getIsDefault,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getReceiverName, description: '收货人', type: 'string'),
        new OA\Property(property: self::getReceiverPhone, description: '收货电话', type: 'string'),
        new OA\Property(property: self::getReceiverRegion, description: '省市区', type: 'string'),
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
            self::getReceiverRegion.'.required' => '请设置省市区',
            self::getReceiverAddress.'.required' => '请设置详细地址',
            self::getIsDefault.'.required' => '请设置默认地址:0=否,1=是',
        ];
    }
}
