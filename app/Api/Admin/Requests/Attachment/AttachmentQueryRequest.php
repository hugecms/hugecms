<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Attachment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AttachmentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getUploaderId, description: '上传者ID', type: 'integer'),
        new OA\Property(property: self::getMimeType, description: 'MIME类型（如：image/jpeg）', type: 'string'),
    ]
)]
class AttachmentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getUploaderId = 'uploaderId';

    public const string getMimeType = 'mimeType';

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
