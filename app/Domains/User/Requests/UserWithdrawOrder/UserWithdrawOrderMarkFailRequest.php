<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderMarkFailRequest',
    required: [
        self::getId,
        self::getReason,
    ],
    properties: [
        new OA\Property(property: self::getId, description: '提现单ID', type: 'integer'),
        new OA\Property(property: self::getReason, description: '打款失败原因', type: 'string'),
    ]
)]
class UserWithdrawOrderMarkFailRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getReason = 'reason';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer|min:1',
            self::getReason => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置提现单ID',
            self::getReason.'.required' => '请设置打款失败原因',
        ];
    }
}
