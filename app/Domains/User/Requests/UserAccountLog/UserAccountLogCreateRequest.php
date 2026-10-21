<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccountLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountLogCreateRequest',
    required: [
        self::getUserId,
        self::getBizType,
        self::getAmount,
        self::getBalanceAfter,
        self::getFrozenAfter,
        self::getBizNo,
        self::getDescription,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getBizType, description: '业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '变动金额,正数入负数出', type: 'string'),
        new OA\Property(property: self::getBalanceAfter, description: '变动后余额快照', type: 'string'),
        new OA\Property(property: self::getFrozenAfter, description: '变动后冻结快照', type: 'string'),
        new OA\Property(property: self::getBizNo, description: '关联业务单号,如提现单号/佣金记录号/充值单号', type: 'string'),
        new OA\Property(property: self::getDescription, description: '变动说明', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserAccountLogCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getBizType = 'bizType';

    public const string getAmount = 'amount';

    public const string getBalanceAfter = 'balanceAfter';

    public const string getFrozenAfter = 'frozenAfter';

    public const string getBizNo = 'bizNo';

    public const string getDescription = 'description';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getBizType => 'required',
            self::getAmount => 'required',
            self::getBalanceAfter => 'required',
            self::getFrozenAfter => 'required',
            self::getBizNo => 'required',
            self::getDescription => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getBizType.'.required' => '请设置业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整',
            self::getAmount.'.required' => '请设置变动金额,正数入负数出',
            self::getBalanceAfter.'.required' => '请设置变动后余额快照',
            self::getFrozenAfter.'.required' => '请设置变动后冻结快照',
            self::getBizNo.'.required' => '请设置关联业务单号,如提现单号/佣金记录号/充值单号',
            self::getDescription.'.required' => '请设置变动说明',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
