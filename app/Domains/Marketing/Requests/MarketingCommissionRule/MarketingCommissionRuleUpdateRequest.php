<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingCommissionRule;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingCommissionRuleUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getInviterId,
        self::getLevel,
        self::getTotalCommission,
        self::getSettledCommission,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getInviterId, description: '上级分销员用户ID,0=无上级', type: 'integer'),
        new OA\Property(property: self::getLevel, description: '分销员等级:1=普通,2=高级', type: 'integer'),
        new OA\Property(property: self::getTotalCommission, description: '累计佣金', type: 'string'),
        new OA\Property(property: self::getSettledCommission, description: '已结算佣金,可提现=total-settled', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审核,1=通过,2=拒绝,3=冻结', type: 'integer'),
    ]
)]
class MarketingCommissionRuleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getInviterId = 'inviterId';

    public const string getLevel = 'level';

    public const string getTotalCommission = 'totalCommission';

    public const string getSettledCommission = 'settledCommission';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getInviterId => 'required',
            self::getLevel => 'required',
            self::getTotalCommission => 'required',
            self::getSettledCommission => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getInviterId.'.required' => '请设置上级分销员用户ID,0=无上级',
            self::getLevel.'.required' => '请设置分销员等级:1=普通,2=高级',
            self::getTotalCommission.'.required' => '请设置累计佣金',
            self::getSettledCommission.'.required' => '请设置已结算佣金,可提现=total-settled',
            self::getStatus.'.required' => '请设置状态:0=待审核,1=通过,2=拒绝,3=冻结',
        ];
    }
}
