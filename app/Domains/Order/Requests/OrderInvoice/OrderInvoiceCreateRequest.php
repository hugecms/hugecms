<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderInvoice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderInvoiceCreateRequest',
    required: [
        self::getOrderId,
        self::getType,
        self::getTitle,
        self::getTaxNo,
        self::getAmount,
        self::getStatus,
        self::getInvoiceNo,
        self::getInvoicedAt,
        self::getRemark,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '发票抬头', type: 'string'),
        new OA\Property(property: self::getTaxNo, description: '纳税人识别号,个人抬头为空', type: 'string'),
        new OA\Property(property: self::getAmount, description: '开票金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待开票,1=已开票,2=已作废', type: 'integer'),
        new OA\Property(property: self::getInvoiceNo, description: '发票号码', type: 'string'),
        new OA\Property(property: self::getInvoicedAt, description: '开票时间', type: 'string'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string'),
    ]
)]
class OrderInvoiceCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getType = 'type';

    public const string getTitle = 'title';

    public const string getTaxNo = 'taxNo';

    public const string getAmount = 'amount';

    public const string getStatus = 'status';

    public const string getInvoiceNo = 'invoiceNo';

    public const string getInvoicedAt = 'invoicedAt';

    public const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getType => 'required',
            self::getTitle => 'required',
            self::getTaxNo => 'required',
            self::getAmount => 'required',
            self::getStatus => 'required',
            self::getInvoiceNo => 'required',
            self::getInvoicedAt => 'required',
            self::getRemark => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getType.'.required' => '请设置类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票',
            self::getTitle.'.required' => '请设置发票抬头',
            self::getTaxNo.'.required' => '请设置纳税人识别号,个人抬头为空',
            self::getAmount.'.required' => '请设置开票金额',
            self::getStatus.'.required' => '请设置状态:0=待开票,1=已开票,2=已作废',
            self::getInvoiceNo.'.required' => '请设置发票号码',
            self::getInvoicedAt.'.required' => '请设置开票时间',
            self::getRemark.'.required' => '请设置备注',
        ];
    }
}
