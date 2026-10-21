<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainItemUpdateRequest',
    required: [
        self::getId,
        self::getActivityId,
        self::getSkuId,
        self::getOriginalPrice,
        self::getFloorPrice,
        self::getMaxCutCount,
        self::getConfig,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '砍价活动ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getOriginalPrice, description: '起始价(发起时售价口径)', type: 'string'),
        new OA\Property(property: self::getFloorPrice, description: '底价(砍到底价即可下单;floor&lt;=original 应用校验)', type: 'string'),
        new OA\Property(property: self::getMaxCutCount, description: '最大帮砍刀数,0=不限,金额收敛到底价', type: 'integer'),
        new OA\Property(property: self::getConfig, description: '玩法参数(随机递减算法边界/首刀上限等,应用层约定)', type: 'string'),
    ]
)]
class MarketingBargainItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getSkuId = 'skuId';

    public const string getOriginalPrice = 'originalPrice';

    public const string getFloorPrice = 'floorPrice';

    public const string getMaxCutCount = 'maxCutCount';

    public const string getConfig = 'config';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getActivityId => 'required',
            self::getSkuId => 'required',
            self::getOriginalPrice => 'required',
            self::getFloorPrice => 'required',
            self::getMaxCutCount => 'required',
            self::getConfig => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getActivityId.'.required' => '请设置砍价活动ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getOriginalPrice.'.required' => '请设置起始价(发起时售价口径)',
            self::getFloorPrice.'.required' => '请设置底价(砍到底价即可下单;floor&lt;=original 应用校验)',
            self::getMaxCutCount.'.required' => '请设置最大帮砍刀数,0=不限,金额收敛到底价',
            self::getConfig.'.required' => '请设置玩法参数(随机递减算法边界/首刀上限等,应用层约定)',
        ];
    }
}
