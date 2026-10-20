<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserProfileCreateRequest',
    required: [
        self::getUserId,
        self::getNickname,
        self::getAvatar,
        self::getGender,
        self::getBirthday,
        self::getBio,
        self::getLocation,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getNickname, description: '昵称', type: 'string'),
        new OA\Property(property: self::getAvatar, description: '头像', type: 'string'),
        new OA\Property(property: self::getGender, description: '性别:0=未知,1=男,2=女', type: 'integer'),
        new OA\Property(property: self::getBirthday, description: '生日', type: 'string'),
        new OA\Property(property: self::getBio, description: '个人简介', type: 'string'),
        new OA\Property(property: self::getLocation, description: '所在地', type: 'string'),
    ]
)]
class UserProfileCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getNickname = 'nickname';

    public const string getAvatar = 'avatar';

    public const string getGender = 'gender';

    public const string getBirthday = 'birthday';

    public const string getBio = 'bio';

    public const string getLocation = 'location';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getNickname => 'required',
            self::getAvatar => 'required',
            self::getGender => 'required',
            self::getBirthday => 'required',
            self::getBio => 'required',
            self::getLocation => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getNickname.'.required' => '请设置昵称',
            self::getAvatar.'.required' => '请设置头像',
            self::getGender.'.required' => '请设置性别:0=未知,1=男,2=女',
            self::getBirthday.'.required' => '请设置生日',
            self::getBio.'.required' => '请设置个人简介',
            self::getLocation.'.required' => '请设置所在地',
        ];
    }
}
