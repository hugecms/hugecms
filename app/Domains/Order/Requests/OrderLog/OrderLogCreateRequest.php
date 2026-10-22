<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderLogCreateRequest',
    required: [
        self::getOrderId,
        self::getFromStatus,
        self::getToStatus,
        self::getOperatorType,
        self::getOperatorId,
        self::getOperator,
        self::getNote,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getFromStatus, description: '变更前状态', type: 'integer'),
        new OA\Property(property: self::getToStatus, description: '变更后状态', type: 'integer'),
        new OA\Property(property: self::getOperatorType, description: '操作账户类型:admin=后台,user=前台,system=系统', type: 'string'),
        new OA\Property(property: self::getOperatorId, description: '操作人ID', type: 'integer'),
        new OA\Property(property: self::getOperator, description: '操作人名称快照', type: 'string'),
        new OA\Property(property: self::getNote, description: '操作备注', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class OrderLogCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getFromStatus = 'fromStatus';

    public const string getToStatus = 'toStatus';

    public const string getOperatorType = 'operatorType';

    public const string getOperatorId = 'operatorId';

    public const string getOperator = 'operator';

    public const string getNote = 'note';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getFromStatus => 'required',
            self::getToStatus => 'required',
            self::getOperatorType => 'required',
            self::getOperatorId => 'required',
            self::getOperator => 'required',
            self::getNote => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getFromStatus.'.required' => '请设置变更前状态',
            self::getToStatus.'.required' => '请设置变更后状态',
            self::getOperatorType.'.required' => '请设置操作账户类型:admin=后台,user=前台,system=系统',
            self::getOperatorId.'.required' => '请设置操作人ID',
            self::getOperator.'.required' => '请设置操作人名称快照',
            self::getNote.'.required' => '请设置操作备注',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
