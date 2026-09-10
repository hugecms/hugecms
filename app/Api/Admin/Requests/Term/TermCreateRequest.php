<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Term;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TermCreateRequest',
    required: [
        self::getTaxonomyId,
        self::getName,
        self::getSlug,
        self::getParentId,
        self::getDescription,
        self::getSort,
        self::getContentCount,
    ],
    properties: [
        new OA\Property(property: self::getTaxonomyId, description: '所属分类法ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '分类项名称（如：科技、体育）', type: 'string'),
        new OA\Property(property: self::getSlug, description: '分类项别名（URL友好）', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID（0代表顶级）', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '分类项描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序权重', type: 'integer'),
        new OA\Property(property: self::getContentCount, description: '该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）', type: 'integer'),
    ]
)]
class TermCreateRequest extends FormRequest
{
    public const string getTaxonomyId = 'taxonomyId';

    public const string getName = 'name';

    public const string getSlug = 'slug';

    public const string getParentId = 'parentId';

    public const string getDescription = 'description';

    public const string getSort = 'sort';

    public const string getContentCount = 'contentCount';

    public function rules(): array
    {
        return [
            self::getTaxonomyId => 'required',
            self::getName => 'required',
            self::getSlug => 'required',
            self::getParentId => 'required',
            self::getDescription => 'required',
            self::getSort => 'required',
            self::getContentCount => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTaxonomyId.'.required' => '请设置所属分类法ID',
            self::getName.'.required' => '请设置分类项名称（如：科技、体育）',
            self::getSlug.'.required' => '请设置分类项别名（URL友好）',
            self::getParentId.'.required' => '请设置父级ID（0代表顶级）',
            self::getDescription.'.required' => '请设置分类项描述',
            self::getSort.'.required' => '请设置排序权重',
            self::getContentCount.'.required' => '请设置该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）',
        ];
    }
}
