<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountQueryRequest',
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserAccountQueryRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public function rules(): array
    {
        return [
            self::getUserId => 'nullable|integer|min:1',
        ];
    }
}
