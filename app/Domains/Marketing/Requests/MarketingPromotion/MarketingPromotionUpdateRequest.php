<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotion;

use App\Domains\Marketing\Entities\MarketingPromotionLadderEntity;
use App\Domains\Marketing\Entities\MarketingPromotionScopeEntity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getType,
        self::getRangeType,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
        self::getLadders,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:full_reduce=满减,discount=折扣', type: 'string'),
        new OA\Property(property: self::getRangeType, description: '适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品', type: 'string'),
        new OA\Property(property: self::getScopes, description: '适用范围列表,rangeType=all 时不传', type: 'array', items: new OA\Items(ref: MarketingPromotionScopeEntity::class)),
        new OA\Property(property: self::getLadders, description: '优惠档位列表,至少一档,满减档必填满减金额/折扣档必填折扣率', type: 'array', items: new OA\Items(ref: MarketingPromotionLadderEntity::class)),
        new OA\Property(property: self::getStartTime, description: '活动开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '活动结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingPromotionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getType = 'type';

    public const string getRangeType = 'rangeType';

    public const string getScopes = 'scopes';

    public const string getLadders = 'ladders';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getType => 'required|in:full_reduce,discount',
            self::getRangeType => 'required|in:all,category,brand,product',
            self::getScopes => 'nullable|array',
            self::getScopes.'.*.scopeType' => 'required_with:'.self::getScopes.'|string|in:category,brand,product',
            self::getScopes.'.*.targetId' => 'required_with:'.self::getScopes.'|integer|min:1',
            self::getLadders => 'required|array|min:1',
            self::getLadders.'.*.minAmount' => 'required|numeric|min:0|distinct',
            self::getLadders.'.*.reduceAmount' => 'nullable|numeric|min:0',
            self::getLadders.'.*.discountRate' => 'nullable|numeric|min:0|max:9.9',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置活动名称',
            self::getType.'.required' => '请设置类型:full_reduce=满减,discount=折扣',
            self::getType.'.in' => '类型错误,仅支持full_reduce=满减,discount=折扣',
            self::getRangeType.'.required' => '请设置适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品',
            self::getRangeType.'.in' => '适用范围错误,仅支持all=全场,category=指定分类,brand=指定品牌,product=指定商品',
            self::getLadders.'.required' => '请设置优惠档位',
            self::getLadders.'.min' => '优惠档位至少一档',
            self::getLadders.'.*.minAmount.required' => '档位门槛金额必填',
            self::getLadders.'.*.minAmount.distinct' => '档位门槛金额不能重复',
            self::getStartTime.'.required' => '请设置活动开始时间',
            self::getEndTime.'.required' => '请设置活动结束时间',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }

    /**
     * 联动校验:类型与档位优惠参数互斥、适用范围与范围列表一致、范围目标去重
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $type = $this->input('type');
                $rangeType = $this->input('rangeType');
                $ladders = is_array($this->input('ladders')) ? $this->input('ladders') : [];
                $scopes = is_array($this->input('scopes')) ? $this->input('scopes') : [];

                foreach ($ladders as $index => $ladder) {
                    if (! is_array($ladder)) {
                        continue;
                    }

                    $minAmount = $ladder['minAmount'] ?? null;
                    $reduceAmount = $ladder['reduceAmount'] ?? null;
                    $discountRate = $ladder['discountRate'] ?? null;

                    if ($type === 'full_reduce') {
                        if ($minAmount !== null && in_array($reduceAmount, [null, ''], true)) {
                            $validator->errors()->add("ladders.{$index}.reduceAmount", '满减活动每档必须设置满减金额');
                        }

                        if ($reduceAmount !== null && $discountRate !== null) {
                            $validator->errors()->add("ladders.{$index}.discountRate", '满减活动档位不能设置折扣率');
                        }
                    }

                    if ($type === 'discount') {
                        if ($minAmount !== null && in_array($discountRate, [null, ''], true)) {
                            $validator->errors()->add("ladders.{$index}.discountRate", '折扣活动每档必须设置折扣率');
                        }

                        if ($discountRate !== null && $reduceAmount !== null) {
                            $validator->errors()->add("ladders.{$index}.reduceAmount", '折扣活动档位不能设置满减金额');
                        }
                    }
                }

                if ($rangeType === 'all') {
                    if ($scopes !== []) {
                        $validator->errors()->add('scopes', '全场活动不能设置适用范围');
                    }
                } elseif ($scopes === []) {
                    $validator->errors()->add('scopes', '请设置适用范围');
                } else {
                    $combinations = [];
                    foreach ($scopes as $index => $scope) {
                        if (! is_array($scope)) {
                            continue;
                        }

                        $scopeType = $scope['scopeType'] ?? '';
                        if ($scopeType !== $rangeType) {
                            $validator->errors()->add("scopes.{$index}.scopeType", '范围类型必须与适用范围一致');
                        }

                        $combination = $scopeType.':'.($scope['targetId'] ?? '');
                        if (in_array($combination, $combinations, true)) {
                            $validator->errors()->add("scopes.{$index}.targetId", '适用范围目标重复');
                        } else {
                            $combinations[] = $combination;
                        }
                    }
                }
            },
        ];
    }
}
