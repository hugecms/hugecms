<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteUpdateRequest',
    required: [
        self::getId,
        self::getSiteName,
        self::getSiteCode,
        self::getDomain,
        self::getDomains,
        self::getSiteLogo,
        self::getFavicon,
        self::getTimezone,
        self::getLanguage,
        self::getTemplateId,
        self::getConfig,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSiteName, description: '站点名称', type: 'string'),
        new OA\Property(property: self::getSiteCode, description: '站点代码（子域名或标识）', type: 'string'),
        new OA\Property(property: self::getDomain, description: '主域名（如：www.example.com）', type: 'string'),
        new OA\Property(property: self::getDomains, description: '附加域名列表（JSON数组）', type: 'string'),
        new OA\Property(property: self::getSiteLogo, description: '站点Logo', type: 'string'),
        new OA\Property(property: self::getFavicon, description: '站点图标', type: 'string'),
        new OA\Property(property: self::getTimezone, description: '时区', type: 'string'),
        new OA\Property(property: self::getLanguage, description: '默认语言', type: 'string'),
        new OA\Property(property: self::getTemplateId, description: '当前使用的模板ID（关联 page_templates，逻辑关联）', type: 'integer'),
        new OA\Property(property: self::getConfig, description: '站点配置（SEO默认值、社交分享等）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class SiteUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSiteName = 'siteName';

    public const string getSiteCode = 'siteCode';

    public const string getDomain = 'domain';

    public const string getDomains = 'domains';

    public const string getSiteLogo = 'siteLogo';

    public const string getFavicon = 'favicon';

    public const string getTimezone = 'timezone';

    public const string getLanguage = 'language';

    public const string getTemplateId = 'templateId';

    public const string getConfig = 'config';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getSiteName => 'required',
            self::getSiteCode => 'required',
            self::getDomain => 'required',
            self::getDomains => 'required',
            self::getSiteLogo => 'required',
            self::getFavicon => 'required',
            self::getTimezone => 'required',
            self::getLanguage => 'required',
            self::getTemplateId => 'required',
            self::getConfig => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getSiteName.'.required' => '请设置站点名称',
            self::getSiteCode.'.required' => '请设置站点代码（子域名或标识）',
            self::getDomain.'.required' => '请设置主域名（如：www.example.com）',
            self::getDomains.'.required' => '请设置附加域名列表（JSON数组）',
            self::getSiteLogo.'.required' => '请设置站点Logo',
            self::getFavicon.'.required' => '请设置站点图标',
            self::getTimezone.'.required' => '请设置时区',
            self::getLanguage.'.required' => '请设置默认语言',
            self::getTemplateId.'.required' => '请设置当前使用的模板ID（关联 page_templates，逻辑关联）',
            self::getConfig.'.required' => '请设置站点配置（SEO默认值、社交分享等）',
            self::getStatus.'.required' => '请设置状态：0停用，1启用',
        ];
    }
}
