<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Option;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OptionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOptionKey, description: '配置键名（storage_config/smtp_config/comment_config等）', type: 'string'),
    ]
)]
class OptionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOptionKey = 'optionKey';

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
