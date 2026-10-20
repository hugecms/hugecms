<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionCreateRequest',
    required: [
        self::getName,
        self::getType,
        self::getRangeType,
        self::getRangeValues,
        self::getMinAmount,
        self::getReduceAmount,
        self::getDiscountRate,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:full_reduce=满减,discount=折扣', type: 'string'),
        new OA\Property(property: self::getRangeType, description: '适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品', type: 'string'),
        new OA\Property(property: self::getRangeValues, description: '范围内ID列表(JSON),all 时为空', type: 'string'),
        new OA\Property(property: self::getMinAmount, description: '门槛金额,0=无门槛', type: 'string'),
        new OA\Property(property: self::getReduceAmount, description: '满减金额,满减活动必填', type: 'string'),
        new OA\Property(property: self::getDiscountRate, description: '折扣率,如8.5表示85折,折扣活动必填', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '活动开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '活动结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingPromotionCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getType = 'type';

    public const string getRangeType = 'rangeType';

    public const string getRangeValues = 'rangeValues';

    public const string getMinAmount = 'minAmount';

    public const string getReduceAmount = 'reduceAmount';

    public const string getDiscountRate = 'discountRate';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getType => 'required',
            self::getRangeType => 'required',
            self::getRangeValues => 'required',
            self::getMinAmount => 'required',
            self::getReduceAmount => 'required',
            self::getDiscountRate => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置活动名称',
            self::getType.'.required' => '请设置类型:full_reduce=满减,discount=折扣',
            self::getRangeType.'.required' => '请设置适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品',
            self::getRangeValues.'.required' => '请设置范围内ID列表(JSON),all 时为空',
            self::getMinAmount.'.required' => '请设置门槛金额,0=无门槛',
            self::getReduceAmount.'.required' => '请设置满减金额,满减活动必填',
            self::getDiscountRate.'.required' => '请设置折扣率,如8.5表示85折,折扣活动必填',
            self::getStartTime.'.required' => '请设置活动开始时间',
            self::getEndTime.'.required' => '请设置活动结束时间',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
