<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\UserMeta;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserMetaQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getMetaKey, description: '元数据键名', type: 'string'),
    ]
)]
class UserMetaQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getMetaKey = 'metaKey';

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
