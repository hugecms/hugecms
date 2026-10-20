<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemApp;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemAppUpdateRequest',
    required: [
        self::getId,
        self::getCode,
        self::getName,
        self::getType,
        self::getAppid,
        self::getSecret,
        self::getConfig,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android', type: 'string'),
        new OA\Property(property: self::getName, description: '应用名称', type: 'string'),
        new OA\Property(property: self::getType, description: '应用类型:web,h5,wechat,app', type: 'string'),
        new OA\Property(property: self::getAppid, description: '微信类应用AppID,非微信应用为空', type: 'string'),
        new OA\Property(property: self::getSecret, description: '应用密钥,加密存储', type: 'string'),
        new OA\Property(property: self::getConfig, description: '扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用,停用即一键下线该端', type: 'integer'),
    ]
)]
class SystemAppUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

    public const string getName = 'name';

    public const string getType = 'type';

    public const string getAppid = 'appid';

    public const string getSecret = 'secret';

    public const string getConfig = 'config';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCode => 'required',
            self::getName => 'required',
            self::getType => 'required',
            self::getAppid => 'required',
            self::getSecret => 'required',
            self::getConfig => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCode.'.required' => '请设置应用标识:web,h5,wechat_official=公众号,wechat_miniapp=小程序,wechat_open=开放平台扫码,app_ios,app_android',
            self::getName.'.required' => '请设置应用名称',
            self::getType.'.required' => '请设置应用类型:web,h5,wechat,app',
            self::getAppid.'.required' => '请设置微信类应用AppID,非微信应用为空',
            self::getSecret.'.required' => '请设置应用密钥,加密存储',
            self::getConfig.'.required' => '请设置扩展配置(JSON):token,aes_key,推送参数,API域名,商户号绑定等',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用,停用即一键下线该端',
        ];
    }
}
