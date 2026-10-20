<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingDistributionRelation;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingDistributionRelationUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getDistributorId,
        self::getLevel,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '被绑定用户ID(下单人)', type: 'integer'),
        new OA\Property(property: self::getDistributorId, description: '分销员用户ID', type: 'integer'),
        new OA\Property(property: self::getLevel, description: '层级:1=一级(直接邀请),2=二级(间接)', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '绑定时间', type: 'string'),
    ]
)]
class MarketingDistributionRelationUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getDistributorId = 'distributorId';

    public const string getLevel = 'level';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getDistributorId => 'required',
            self::getLevel => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置被绑定用户ID(下单人)',
            self::getDistributorId.'.required' => '请设置分销员用户ID',
            self::getLevel.'.required' => '请设置层级:1=一级(直接邀请),2=二级(间接)',
            self::getCreateTime.'.required' => '请设置绑定时间',
        ];
    }
}
