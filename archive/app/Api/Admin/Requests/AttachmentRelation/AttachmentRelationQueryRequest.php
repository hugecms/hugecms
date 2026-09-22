<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\AttachmentRelation;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AttachmentRelationQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '关联的内容ID', type: 'integer'),
        new OA\Property(property: self::getFieldKey, description: '关联到内容的哪个字段（如：封面图、详情图集）', type: 'string'),
    ]
)]
class AttachmentRelationQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getFieldKey = 'fieldKey';

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
