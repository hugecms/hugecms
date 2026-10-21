<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainRecordUpdateRequest',
    required: [
        self::getId,
        self::getBargainNo,
        self::getItemId,
        self::getUserId,
        self::getCurrentPrice,
        self::getCutCount,
        self::getStatus,
        self::getExpireTime,
        self::getSuccessTime,
        self::getOrderNo,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getBargainNo, description: '砍价单号,全局唯一', type: 'string'),
        new OA\Property(property: self::getItemId, description: '砍价场次商品ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '发起人用户ID', type: 'integer'),
        new OA\Property(property: self::getCurrentPrice, description: '当前价,逐刀递减且&gt;=floor_price由条件更新保证', type: 'string'),
        new OA\Property(property: self::getCutCount, description: '已砍刀数', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消', type: 'integer'),
        new OA\Property(property: self::getExpireTime, description: '砍价截止时间,超时扫描依据', type: 'string'),
        new OA\Property(property: self::getSuccessTime, description: '到底价时间', type: 'string'),
        new OA\Property(property: self::getOrderNo, description: '成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束', type: 'string'),
    ]
)]
class MarketingBargainRecordUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getBargainNo = 'bargainNo';

    public const string getItemId = 'itemId';

    public const string getUserId = 'userId';

    public const string getCurrentPrice = 'currentPrice';

    public const string getCutCount = 'cutCount';

    public const string getStatus = 'status';

    public const string getExpireTime = 'expireTime';

    public const string getSuccessTime = 'successTime';

    public const string getOrderNo = 'orderNo';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getBargainNo => 'required',
            self::getItemId => 'required',
            self::getUserId => 'required',
            self::getCurrentPrice => 'required',
            self::getCutCount => 'required',
            self::getStatus => 'required',
            self::getExpireTime => 'required',
            self::getSuccessTime => 'required',
            self::getOrderNo => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getBargainNo.'.required' => '请设置砍价单号,全局唯一',
            self::getItemId.'.required' => '请设置砍价场次商品ID',
            self::getUserId.'.required' => '请设置发起人用户ID',
            self::getCurrentPrice.'.required' => '请设置当前价,逐刀递减且&gt;=floor_price由条件更新保证',
            self::getCutCount.'.required' => '请设置已砍刀数',
            self::getStatus.'.required' => '请设置状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消',
            self::getExpireTime.'.required' => '请设置砍价截止时间,超时扫描依据',
            self::getSuccessTime.'.required' => '请设置到底价时间',
            self::getOrderNo.'.required' => '请设置成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束',
        ];
    }
}
