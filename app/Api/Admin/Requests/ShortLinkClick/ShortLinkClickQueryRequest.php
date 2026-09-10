<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ShortLinkClick;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShortLinkClickQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShortLinkId, description: '短链接ID', type: 'integer'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class ShortLinkClickQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShortLinkId = 'shortLinkId';

    public const string getCreatedAt = 'createdAt';

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
