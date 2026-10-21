<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyTeam;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyTeamQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '拼团活动ID', type: 'integer'),
        new OA\Property(property: self::getLeaderUserId, description: '团长用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=拼团中,2=已成团,3=已解散(超时/取消)', type: 'integer'),
        new OA\Property(property: self::getExpireTime, description: '成团截止时间,超时扫描依据', type: 'string'),
    ]
)]
class MarketingGroupBuyTeamQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getLeaderUserId = 'leaderUserId';

    public const string getStatus = 'status';

    public const string getExpireTime = 'expireTime';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
