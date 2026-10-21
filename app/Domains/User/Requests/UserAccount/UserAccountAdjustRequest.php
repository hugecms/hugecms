<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountAdjustRequest',
    required: [
        self::getUserId,
        self::getAmount,
        self::getDescription,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '调整金额,有符号,负数扣减可致欠款', type: 'string'),
        new OA\Property(property: self::getBizNo, description: '关联业务单号', type: 'string'),
        new OA\Property(property: self::getDescription, description: '调整说明', type: 'string'),
    ]
)]
class UserAccountAdjustRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getAmount = 'amount';

    public const string getBizNo = 'bizNo';

    public const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getUserId => 'required|integer|min:1',
            self::getAmount => 'required|numeric|not_in:0',
            self::getBizNo => 'nullable|string|max:64',
            self::getDescription => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getAmount.'.required' => '请设置调整金额',
            self::getAmount.'.not_in' => '调整金额不能为零',
            self::getDescription.'.required' => '请设置调整说明',
        ];
    }
}
