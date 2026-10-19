<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemDictItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemDictItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getDictId, description: '字典类型ID', type: 'integer'),
    ]
)]
class SystemDictItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getDictId = 'dictId';

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
