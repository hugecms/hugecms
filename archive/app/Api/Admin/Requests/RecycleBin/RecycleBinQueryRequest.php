<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\RecycleBin;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RecycleBinQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTargetType, description: '原对象类型：content/term/attachment/user/form_submission/comment', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '原对象ID', type: 'string'),
        new OA\Property(property: self::getExpireAt, description: '过期时间（虚拟生成列）', type: 'string'),
    ]
)]
class RecycleBinQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTargetType = 'targetType';

    public const string getTargetId = 'targetId';

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
