<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ShortLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShortLinkQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShortCode, description: '短链代码（如：abc123）', type: 'string'),
        new OA\Property(property: self::getExpireAt, description: '过期时间（NULL永不过期）', type: 'string'),
    ]
)]
class ShortLinkQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShortCode = 'shortCode';

    public const string getExpireAt = 'expireAt';

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
