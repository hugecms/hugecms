<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserFollower;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFollowerQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getUserId, description: '被关注用户ID', type: 'integer'),
        new OA\Property(property: self::getFollowerId, description: '关注者用户ID', type: 'integer'),
    ]
)]
class UserFollowerQueryRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getFollowerId = 'followerId';

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
