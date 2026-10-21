<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRiskRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRiskRecordCreateRequest',
    required: [
        self::getUserId,
        self::getRuleId,
        self::getObjectType,
        self::getObjectNo,
        self::getAction,
        self::getAppealStatus,
        self::getAppealNote,
        self::getHandleNote,
        self::getHandledAt,
        self::getRemark,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '触发用户ID', type: 'integer'),
        new OA\Property(property: self::getRuleId, description: '命中规则ID,关联system_risk_rules', type: 'integer'),
        new OA\Property(property: self::getObjectType, description: '对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)', type: 'integer'),
        new OA\Property(property: self::getObjectNo, description: '对象单号,如提现单号/订单号', type: 'string'),
        new OA\Property(property: self::getAction, description: '处置快照:1=拦截,2=标记放行', type: 'integer'),
        new OA\Property(property: self::getAppealStatus, description: '申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回', type: 'integer'),
        new OA\Property(property: self::getAppealNote, description: '申诉理由', type: 'string'),
        new OA\Property(property: self::getHandleNote, description: '申诉处理说明', type: 'string'),
        new OA\Property(property: self::getHandledAt, description: '申诉处理时间', type: 'string'),
        new OA\Property(property: self::getRemark, description: '命中详情,如规则参数与实际值', type: 'string'),
    ]
)]
class SystemRiskRecordCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getRuleId = 'ruleId';

    public const string getObjectType = 'objectType';

    public const string getObjectNo = 'objectNo';

    public const string getAction = 'action';

    public const string getAppealStatus = 'appealStatus';

    public const string getAppealNote = 'appealNote';

    public const string getHandleNote = 'handleNote';

    public const string getHandledAt = 'handledAt';

    public const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getRuleId => 'required',
            self::getObjectType => 'required',
            self::getObjectNo => 'required',
            self::getAction => 'required',
            self::getAppealStatus => 'required',
            self::getAppealNote => 'required',
            self::getHandleNote => 'required',
            self::getHandledAt => 'required',
            self::getRemark => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置触发用户ID',
            self::getRuleId.'.required' => '请设置命中规则ID,关联system_risk_rules',
            self::getObjectType.'.required' => '请设置对象类型:1=订单,2=优惠券,3=提现,4=售后,5=裂变活动(助力/帮砍)',
            self::getObjectNo.'.required' => '请设置对象单号,如提现单号/订单号',
            self::getAction.'.required' => '请设置处置快照:1=拦截,2=标记放行',
            self::getAppealStatus.'.required' => '请设置申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回',
            self::getAppealNote.'.required' => '请设置申诉理由',
            self::getHandleNote.'.required' => '请设置申诉处理说明',
            self::getHandledAt.'.required' => '请设置申诉处理时间',
            self::getRemark.'.required' => '请设置命中详情,如规则参数与实际值',
        ];
    }
}
