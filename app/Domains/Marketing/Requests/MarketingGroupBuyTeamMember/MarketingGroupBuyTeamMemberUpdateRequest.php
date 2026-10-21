<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyTeamMember;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyTeamMemberUpdateRequest',
    required: [
        self::getId,
        self::getTeamId,
        self::getUserId,
        self::getOrderNo,
        self::getJoinTime,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTeamId, description: '团ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '成员用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '成员订单号,关联orders.order_no', type: 'string'),
        new OA\Property(property: self::getJoinTime, description: '参团时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class MarketingGroupBuyTeamMemberUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTeamId = 'teamId';

    public const string getUserId = 'userId';

    public const string getOrderNo = 'orderNo';

    public const string getJoinTime = 'joinTime';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getTeamId => 'required',
            self::getUserId => 'required',
            self::getOrderNo => 'required',
            self::getJoinTime => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getTeamId.'.required' => '请设置团ID',
            self::getUserId.'.required' => '请设置成员用户ID',
            self::getOrderNo.'.required' => '请设置成员订单号,关联orders.order_no',
            self::getJoinTime.'.required' => '请设置参团时间',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
