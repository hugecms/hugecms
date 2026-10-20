<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Requests\ShippingShipmentTrack;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingShipmentTrackCreateRequest',
    required: [
        self::getShipmentId,
        self::getAcceptTime,
        self::getLocation,
        self::getDescription,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getShipmentId, description: '发货单ID', type: 'integer'),
        new OA\Property(property: self::getAcceptTime, description: '轨迹发生时间', type: 'string'),
        new OA\Property(property: self::getLocation, description: '所在城市/网点', type: 'string'),
        new OA\Property(property: self::getDescription, description: '轨迹描述,如:已签收', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class ShippingShipmentTrackCreateRequest extends FormRequest
{
    public const string getShipmentId = 'shipmentId';

    public const string getAcceptTime = 'acceptTime';

    public const string getLocation = 'location';

    public const string getDescription = 'description';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getShipmentId => 'required',
            self::getAcceptTime => 'required',
            self::getLocation => 'required',
            self::getDescription => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShipmentId.'.required' => '请设置发货单ID',
            self::getAcceptTime.'.required' => '请设置轨迹发生时间',
            self::getLocation.'.required' => '请设置所在城市/网点',
            self::getDescription.'.required' => '请设置轨迹描述,如:已签收',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
