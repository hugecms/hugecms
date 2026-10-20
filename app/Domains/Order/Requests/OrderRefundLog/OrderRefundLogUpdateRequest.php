<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefundLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundLogUpdateRequest',
    required: [
        self::getId,
        self::getRefundId,
        self::getOperatorType,
        self::getOperatorId,
        self::getOperator,
        self::getContent,
        self::getImages,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundId, description: '退款单ID', type: 'integer'),
        new OA\Property(property: self::getOperatorType, description: '操作账户类型:admin,user,system', type: 'string'),
        new OA\Property(property: self::getOperatorId, description: '操作人ID', type: 'integer'),
        new OA\Property(property: self::getOperator, description: '操作人名称快照', type: 'string'),
        new OA\Property(property: self::getContent, description: '协商内容/操作说明', type: 'string'),
        new OA\Property(property: self::getImages, description: '凭证图片(JSON数组)', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class OrderRefundLogUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundId = 'refundId';

    public const string getOperatorType = 'operatorType';

    public const string getOperatorId = 'operatorId';

    public const string getOperator = 'operator';

    public const string getContent = 'content';

    public const string getImages = 'images';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRefundId => 'required',
            self::getOperatorType => 'required',
            self::getOperatorId => 'required',
            self::getOperator => 'required',
            self::getContent => 'required',
            self::getImages => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRefundId.'.required' => '请设置退款单ID',
            self::getOperatorType.'.required' => '请设置操作账户类型:admin,user,system',
            self::getOperatorId.'.required' => '请设置操作人ID',
            self::getOperator.'.required' => '请设置操作人名称快照',
            self::getContent.'.required' => '请设置协商内容/操作说明',
            self::getImages.'.required' => '请设置凭证图片(JSON数组)',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
