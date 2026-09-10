<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\SeoMeta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SeoMetaCreateRequest',
    required: [
        self::getTargetType,
        self::getTargetId,
        self::getTitle,
        self::getKeywords,
        self::getDescription,
        self::getCanonicalUrl,
        self::getRobots,
    ],
    properties: [
        new OA\Property(property: self::getTargetType, description: '目标类型：content/term/custom_page', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '对应的目标实体ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: 'SEO标题（浏览器Tab显示）', type: 'string'),
        new OA\Property(property: self::getKeywords, description: 'SEO关键词（逗号分隔）', type: 'string'),
        new OA\Property(property: self::getDescription, description: 'SEO描述（搜索结果展示）', type: 'string'),
        new OA\Property(property: self::getCanonicalUrl, description: '权威链接（防止重复页）', type: 'string'),
        new OA\Property(property: self::getRobots, description: '机器人抓取策略', type: 'string'),
    ]
)]
class SeoMetaCreateRequest extends FormRequest
{
    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

    public const string getTitle = 'title';

    public const string getKeywords = 'keywords';

    public const string getDescription = 'description';

    public const string getCanonicalUrl = 'canonicalUrl';

    public const string getRobots = 'robots';

    public function rules(): array
    {
        return [
            self::getTargetType => 'required',
            self::getTargetId => 'required',
            self::getTitle => 'required',
            self::getKeywords => 'required',
            self::getDescription => 'required',
            self::getCanonicalUrl => 'required',
            self::getRobots => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTargetType.'.required' => '请设置目标类型：content/term/custom_page',
            self::getTargetId.'.required' => '请设置对应的目标实体ID',
            self::getTitle.'.required' => '请设置SEO标题（浏览器Tab显示）',
            self::getKeywords.'.required' => '请设置SEO关键词（逗号分隔）',
            self::getDescription.'.required' => '请设置SEO描述（搜索结果展示）',
            self::getCanonicalUrl.'.required' => '请设置权威链接（防止重复页）',
            self::getRobots.'.required' => '请设置机器人抓取策略',
        ];
    }
}
