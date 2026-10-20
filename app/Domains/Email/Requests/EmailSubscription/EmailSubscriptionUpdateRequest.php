<?php

declare(strict_types=1);

namespace App\Domains\Email\Requests\EmailSubscription;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailSubscriptionUpdateRequest',
    required: [
        self::getId,
        self::getEmail,
        self::getStatus,
        self::getToken,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '订阅邮箱', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=已退订,1=订阅中', type: 'integer'),
        new OA\Property(property: self::getToken, description: '退订令牌', type: 'string'),
    ]
)]
class EmailSubscriptionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getEmail = 'email';

    public const string getStatus = 'status';

    public const string getToken = 'token';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getEmail => 'required',
            self::getStatus => 'required',
            self::getToken => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getEmail.'.required' => '请设置订阅邮箱',
            self::getStatus.'.required' => '请设置状态:0=已退订,1=订阅中',
            self::getToken.'.required' => '请设置退订令牌',
        ];
    }
}
