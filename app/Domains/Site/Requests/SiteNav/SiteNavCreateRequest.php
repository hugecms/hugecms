<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteNav;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteNavCreateRequest',
    required: [
        self::getParentId,
        self::getName,
        self::getUrl,
        self::getPosition,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getName, description: '导航名称', type: 'string'),
        new OA\Property(property: self::getUrl, description: '链接地址', type: 'string'),
        new OA\Property(property: self::getPosition, description: '展示位置:header=头部,footer=底部', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class SiteNavCreateRequest extends FormRequest
{
    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getUrl = 'url';

    public const string getPosition = 'position';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getParentId => 'required',
            self::getName => 'required',
            self::getUrl => 'required',
            self::getPosition => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentId.'.required' => '请设置父级ID,0=顶级',
            self::getName.'.required' => '请设置导航名称',
            self::getUrl.'.required' => '请设置链接地址',
            self::getPosition.'.required' => '请设置展示位置:header=头部,footer=底部',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=隐藏,1=显示',
        ];
    }
}
