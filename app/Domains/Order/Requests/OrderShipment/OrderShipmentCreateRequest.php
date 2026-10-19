<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderShipment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderShipmentCreateRequest',
    required: [
        self::getOrderId,
        self::getExpressCompany,
        self::getExpressNo,
        self::getRemark,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getExpressCompany, description: '快递公司', type: 'string'),
        new OA\Property(property: self::getExpressNo, description: '运单号', type: 'string'),
        new OA\Property(property: self::getRemark, description: '发货备注', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class OrderShipmentCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getExpressCompany = 'expressCompany';

    public const string getExpressNo = 'expressNo';

    public const string getRemark = 'remark';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getExpressCompany => 'required',
            self::getExpressNo => 'required',
            self::getRemark => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getExpressCompany.'.required' => '请设置快递公司',
            self::getExpressNo.'.required' => '请设置运单号',
            self::getRemark.'.required' => '请设置发货备注',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
