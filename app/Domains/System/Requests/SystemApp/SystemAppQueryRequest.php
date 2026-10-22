<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemApp;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemAppQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '应用标识:web=PC网站,h5=移动网页,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios=iOS应用,app_android=Android应用', type: 'string'),
        new OA\Property(property: self::getAppid, description: '微信类应用AppID,非微信应用为空', type: 'string'),
    ]
)]
class SystemAppQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

    public const string getAppid = 'appid';

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
