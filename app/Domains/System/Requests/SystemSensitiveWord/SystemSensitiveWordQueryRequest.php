<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemSensitiveWord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemSensitiveWordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getWord, description: '敏感词', type: 'string'),
    ]
)]
class SystemSensitiveWordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getWord = 'word';

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
