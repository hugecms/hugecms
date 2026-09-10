<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentUpdateRequest',
    required: [
        self::getId,
        self::getModelId,
        self::getTitle,
        self::getSlug,
        self::getAuthorId,
        self::getStatus,
        self::getVisibility,
        self::getPassword,
        self::getViews,
        self::getCommentCount,
        self::getSort,
        self::getIsTop,
        self::getPublishedAt,
        self::getAuditStatus,
        self::getAuditRemark,
        self::getAuditorId,
        self::getAuditedAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getModelId, description: '所属模型ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '内容标题', type: 'string'),
        new OA\Property(property: self::getSlug, description: 'URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）', type: 'string'),
        new OA\Property(property: self::getAuthorId, description: '发布者用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站', type: 'string'),
        new OA\Property(property: self::getVisibility, description: '可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）', type: 'string'),
        new OA\Property(property: self::getPassword, description: '密码保护口令（visibility=password 时使用，哈希存储）', type: 'string'),
        new OA\Property(property: self::getViews, description: '浏览量计数', type: 'integer'),
        new OA\Property(property: self::getCommentCount, description: '评论数（审核通过的冗余计数，避免列表页逐条COUNT）', type: 'integer'),
        new OA\Property(property: self::getSort, description: '手动排序权重（数值越大越靠前）', type: 'integer'),
        new OA\Property(property: self::getIsTop, description: '是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）', type: 'integer'),
        new OA\Property(property: self::getPublishedAt, description: '计划/实际发布时间', type: 'string'),
        new OA\Property(property: self::getAuditStatus, description: '审核状态：pending待审核/approved通过/rejected驳回', type: 'string'),
        new OA\Property(property: self::getAuditRemark, description: '审核备注（驳回原因）', type: 'string'),
        new OA\Property(property: self::getAuditorId, description: '审核人ID', type: 'integer'),
        new OA\Property(property: self::getAuditedAt, description: '审核时间', type: 'string'),
    ]
)]
class ContentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getModelId = 'modelId';

    public const string getTitle = 'title';

    public const string getSlug = 'slug';

    public const string getAuthorId = 'authorId';

    public const string getStatus = 'status';

    public const string getVisibility = 'visibility';

    public const string getPassword = 'password';

    public const string getViews = 'views';

    public const string getCommentCount = 'commentCount';

    public const string getSort = 'sort';

    public const string getIsTop = 'isTop';

    public const string getPublishedAt = 'publishedAt';

    public const string getAuditStatus = 'auditStatus';

    public const string getAuditRemark = 'auditRemark';

    public const string getAuditorId = 'auditorId';

    public const string getAuditedAt = 'auditedAt';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer',
            self::getModelId => 'required|integer',
            self::getTitle => 'required|string|max:200',
            self::getSlug => 'nullable|string|max:200',
            self::getAuthorId => 'required|integer',
            self::getStatus => 'required|string|max:20',
            self::getVisibility => 'nullable|string|max:20',
            self::getPassword => 'nullable|string|max:255',
            self::getViews => 'nullable|integer|min:0',
            self::getCommentCount => 'nullable|integer|min:0',
            self::getSort => 'nullable|integer',
            self::getIsTop => 'nullable|integer|in:0,1',
            self::getPublishedAt => 'nullable|date',
            self::getAuditStatus => 'nullable|string|max:20',
            self::getAuditRemark => 'nullable|string|max:255',
            self::getAuditorId => 'nullable|integer',
            self::getAuditedAt => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getModelId.'.required' => '请设置所属模型ID',
            self::getTitle.'.required' => '请设置内容标题',
            self::getSlug.'.required' => '请设置URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）',
            self::getAuthorId.'.required' => '请设置发布者用户ID',
            self::getStatus.'.required' => '请设置状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站',
            self::getVisibility.'.required' => '请设置可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）',
            self::getPassword.'.required' => '请设置密码保护口令（visibility=password 时使用，哈希存储）',
            self::getViews.'.required' => '请设置浏览量计数',
            self::getCommentCount.'.required' => '请设置评论数（审核通过的冗余计数，避免列表页逐条COUNT）',
            self::getSort.'.required' => '请设置手动排序权重（数值越大越靠前）',
            self::getIsTop.'.required' => '请设置是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）',
            self::getPublishedAt.'.required' => '请设置计划/实际发布时间',
            self::getAuditStatus.'.required' => '请设置审核状态：pending待审核/approved通过/rejected驳回',
            self::getAuditRemark.'.required' => '请设置审核备注（驳回原因）',
            self::getAuditorId.'.required' => '请设置审核人ID',
            self::getAuditedAt.'.required' => '请设置审核时间',
        ];
    }
}
