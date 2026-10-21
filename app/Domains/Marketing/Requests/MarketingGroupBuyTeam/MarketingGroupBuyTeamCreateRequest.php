<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyTeam;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyTeamCreateRequest',
    required: [
        self::getActivityId,
        self::getLeaderUserId,
        self::getStatus,
        self::getMemberCount,
        self::getExpireTime,
        self::getSuccessTime,
    ],
    properties: [
        new OA\Property(property: self::getActivityId, description: '拼团活动ID', type: 'integer'),
        new OA\Property(property: self::getLeaderUserId, description: '团长用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=拼团中,2=已成团,3=已解散(超时/取消)', type: 'integer'),
        new OA\Property(property: self::getMemberCount, description: '当前成员数', type: 'integer'),
        new OA\Property(property: self::getExpireTime, description: '成团截止时间,超时扫描依据', type: 'string'),
        new OA\Property(property: self::getSuccessTime, description: '成团时间', type: 'string'),
    ]
)]
class MarketingGroupBuyTeamCreateRequest extends FormRequest
{
    public const string getActivityId = 'activityId';

    public const string getLeaderUserId = 'leaderUserId';

    public const string getStatus = 'status';

    public const string getMemberCount = 'memberCount';

    public const string getExpireTime = 'expireTime';

    public const string getSuccessTime = 'successTime';

    public function rules(): array
    {
        return [
            self::getActivityId => 'required',
            self::getLeaderUserId => 'required',
            self::getStatus => 'required',
            self::getMemberCount => 'required',
            self::getExpireTime => 'required',
            self::getSuccessTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActivityId.'.required' => '请设置拼团活动ID',
            self::getLeaderUserId.'.required' => '请设置团长用户ID',
            self::getStatus.'.required' => '请设置状态:1=拼团中,2=已成团,3=已解散(超时/取消)',
            self::getMemberCount.'.required' => '请设置当前成员数',
            self::getExpireTime.'.required' => '请设置成团截止时间,超时扫描依据',
            self::getSuccessTime.'.required' => '请设置成团时间',
        ];
    }
}
