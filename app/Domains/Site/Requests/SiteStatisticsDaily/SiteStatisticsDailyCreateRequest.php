<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteStatisticsDaily;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteStatisticsDailyCreateRequest',
    required: [
        self::getStatDate,
        self::getPv,
        self::getUv,
        self::getNewUsers,
        self::getOrderCount,
        self::getOrderAmount,
        self::getPayCount,
        self::getPayAmount,
        self::getRefundCount,
        self::getRefundAmount,
    ],
    properties: [
        new OA\Property(property: self::getStatDate, description: '统计日期', type: 'string'),
        new OA\Property(property: self::getPv, description: '页面浏览量', type: 'integer'),
        new OA\Property(property: self::getUv, description: '独立访客数', type: 'integer'),
        new OA\Property(property: self::getNewUsers, description: '新增注册用户数', type: 'integer'),
        new OA\Property(property: self::getOrderCount, description: '下单笔数', type: 'integer'),
        new OA\Property(property: self::getOrderAmount, description: '下单金额', type: 'string'),
        new OA\Property(property: self::getPayCount, description: '支付笔数', type: 'integer'),
        new OA\Property(property: self::getPayAmount, description: '支付金额', type: 'string'),
        new OA\Property(property: self::getRefundCount, description: '退款笔数', type: 'integer'),
        new OA\Property(property: self::getRefundAmount, description: '退款金额', type: 'string'),
    ]
)]
class SiteStatisticsDailyCreateRequest extends FormRequest
{
    public const string getStatDate = 'statDate';

    public const string getPv = 'pv';

    public const string getUv = 'uv';

    public const string getNewUsers = 'newUsers';

    public const string getOrderCount = 'orderCount';

    public const string getOrderAmount = 'orderAmount';

    public const string getPayCount = 'payCount';

    public const string getPayAmount = 'payAmount';

    public const string getRefundCount = 'refundCount';

    public const string getRefundAmount = 'refundAmount';

    public function rules(): array
    {
        return [
            self::getStatDate => 'required',
            self::getPv => 'required',
            self::getUv => 'required',
            self::getNewUsers => 'required',
            self::getOrderCount => 'required',
            self::getOrderAmount => 'required',
            self::getPayCount => 'required',
            self::getPayAmount => 'required',
            self::getRefundCount => 'required',
            self::getRefundAmount => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatDate.'.required' => '请设置统计日期',
            self::getPv.'.required' => '请设置页面浏览量',
            self::getUv.'.required' => '请设置独立访客数',
            self::getNewUsers.'.required' => '请设置新增注册用户数',
            self::getOrderCount.'.required' => '请设置下单笔数',
            self::getOrderAmount.'.required' => '请设置下单金额',
            self::getPayCount.'.required' => '请设置支付笔数',
            self::getPayAmount.'.required' => '请设置支付金额',
            self::getRefundCount.'.required' => '请设置退款笔数',
            self::getRefundAmount.'.required' => '请设置退款金额',
        ];
    }
}
