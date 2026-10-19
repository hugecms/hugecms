<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemDict;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemDictQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型标识,如:gender', type: 'string'),
    ]
)]
class SystemDictQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getType = 'type';

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
