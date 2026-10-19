<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemAttachment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemAttachmentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getModule, description: '所属模块:article,product', type: 'string'),
    ]
)]
class SystemAttachmentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getModule = 'module';

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
