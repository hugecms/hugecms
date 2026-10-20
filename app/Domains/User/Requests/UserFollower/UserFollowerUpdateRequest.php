<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserFollower;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFollowerUpdateRequest',
    required: [
        self::getUserId,
        self::getFollowerId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '被关注用户ID', type: 'integer'),
        new OA\Property(property: self::getFollowerId, description: '关注者用户ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserFollowerUpdateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getFollowerId = 'followerId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getFollowerId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置被关注用户ID',
            self::getFollowerId.'.required' => '请设置关注者用户ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
