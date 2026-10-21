<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountCreateRequest',
    required: [
        self::getUserId,
        self::getBalance,
        self::getFrozen,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getBalance, description: '账户余额,可为负=欠款,欠款不可用于消费/提现', type: 'string'),
        new OA\Property(property: self::getFrozen, description: '冻结金额,提现审核/打款中在途', type: 'string'),
    ]
)]
class UserAccountCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getBalance = 'balance';

    public const string getFrozen = 'frozen';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getBalance => 'required',
            self::getFrozen => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getBalance.'.required' => '请设置账户余额,可为负=欠款,欠款不可用于消费/提现',
            self::getFrozen.'.required' => '请设置冻结金额,提现审核/打款中在途',
        ];
    }
}
