<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserAccountQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

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
