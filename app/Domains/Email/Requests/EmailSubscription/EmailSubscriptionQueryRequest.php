<?php

declare(strict_types=1);

namespace App\Domains\Email\Requests\EmailSubscription;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailSubscriptionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '订阅邮箱', type: 'string'),
    ]
)]
class EmailSubscriptionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getEmail = 'email';

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
