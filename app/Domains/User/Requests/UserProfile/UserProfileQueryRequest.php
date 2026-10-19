<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserProfileQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserProfileQueryRequest extends FormRequest
{
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
