<?php

declare(strict_types=1);

namespace App\Domains\Site\Requests\SiteSearchKeyword;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SiteSearchKeywordUpdateRequest',
    required: [
        self::getId,
        self::getKeyword,
        self::getSearchCount,
        self::getResultCount,
        self::getLastSearchedAt,
        self::getIsHot,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '搜索词', type: 'string'),
        new OA\Property(property: self::getSearchCount, description: '搜索次数', type: 'integer'),
        new OA\Property(property: self::getResultCount, description: '最近一次结果数,0=无结果', type: 'integer'),
        new OA\Property(property: self::getLastSearchedAt, description: '最后搜索时间', type: 'string'),
        new OA\Property(property: self::getIsHot, description: '人工推荐热词:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=屏蔽,1=正常', type: 'integer'),
    ]
)]
class SiteSearchKeywordUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getSearchCount = 'searchCount';

    public const string getResultCount = 'resultCount';

    public const string getLastSearchedAt = 'lastSearchedAt';

    public const string getIsHot = 'isHot';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getKeyword => 'required',
            self::getSearchCount => 'required',
            self::getResultCount => 'required',
            self::getLastSearchedAt => 'required',
            self::getIsHot => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getKeyword.'.required' => '请设置搜索词',
            self::getSearchCount.'.required' => '请设置搜索次数',
            self::getResultCount.'.required' => '请设置最近一次结果数,0=无结果',
            self::getLastSearchedAt.'.required' => '请设置最后搜索时间',
            self::getIsHot.'.required' => '请设置人工推荐热词:0=否,1=是',
            self::getStatus.'.required' => '请设置状态:0=屏蔽,1=正常',
        ];
    }
}
