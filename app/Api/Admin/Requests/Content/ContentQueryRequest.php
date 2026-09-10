<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSlug, description: 'URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站', type: 'string'),
        new OA\Property(property: self::getPublishedAt, description: '计划/实际发布时间', type: 'string'),
    ]
)]
class ContentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSlug = 'slug';

    public const string getStatus = 'status';

    public const string getPublishedAt = 'publishedAt';

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
