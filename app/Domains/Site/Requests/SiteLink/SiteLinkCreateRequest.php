<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteLinkCreateRequest',
    required: [
        self::getName,
        self::getUrl,
        self::getLogo,
        self::getDescription,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '站点名称', type: 'string'),
        new OA\Property(property: self::getUrl, description: '链接地址', type: 'string'),
        new OA\Property(property: self::getLogo, description: '站点LOGO', type: 'string'),
        new OA\Property(property: self::getDescription, description: '描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SiteLinkCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getUrl = 'url';

    public const string getLogo = 'logo';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getUrl => 'required',
            self::getLogo => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置站点名称',
            self::getUrl.'.required' => '请设置链接地址',
            self::getLogo.'.required' => '请设置站点LOGO',
            self::getDescription.'.required' => '请设置描述',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
