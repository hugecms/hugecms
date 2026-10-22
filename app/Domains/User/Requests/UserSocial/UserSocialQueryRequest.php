<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSocial;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSocialQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getPlatform, description: '平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub', type: 'string'),
        new OA\Property(property: self::getOpenId, description: '第三方OpenID', type: 'string'),
    ]
)]
class UserSocialQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getPlatform = 'platform';

    public const string getOpenId = 'openId';

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
