<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AttachmentRelation;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AttachmentRelationCreateRequest',
    required: [
        self::getAttachmentId,
        self::getContentId,
        self::getFieldKey,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getAttachmentId, description: '附件ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '关联的内容ID', type: 'integer'),
        new OA\Property(property: self::getFieldKey, description: '关联到内容的哪个字段（如：封面图、详情图集）', type: 'string'),
        new OA\Property(property: self::getSort, description: '在该内容下的排序', type: 'integer'),
    ]
)]
class AttachmentRelationCreateRequest extends FormRequest
{
    public const string getAttachmentId = 'attachmentId';

    public const string getContentId = 'contentId';

    public const string getFieldKey = 'fieldKey';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getAttachmentId => 'required',
            self::getContentId => 'required',
            self::getFieldKey => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAttachmentId.'.required' => '请设置附件ID',
            self::getContentId.'.required' => '请设置关联的内容ID',
            self::getFieldKey.'.required' => '请设置关联到内容的哪个字段（如：封面图、详情图集）',
            self::getSort.'.required' => '请设置在该内容下的排序',
        ];
    }
}
