<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderMarkSuccessRequest',
    required: [
        self::getId,
        self::getChannelOrderNo,
    ],
    properties: [
        new OA\Property(property: self::getId, description: '提现单ID', type: 'integer'),
        new OA\Property(property: self::getChannelOrderNo, description: '渠道打款单号(线下打款回填转账凭证号)', type: 'string'),
    ]
)]
class UserWithdrawOrderMarkSuccessRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getChannelOrderNo = 'channelOrderNo';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer|min:1',
            self::getChannelOrderNo => 'required|string|max:64',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置提现单ID',
            self::getChannelOrderNo.'.required' => '请设置渠道打款单号',
        ];
    }
}
