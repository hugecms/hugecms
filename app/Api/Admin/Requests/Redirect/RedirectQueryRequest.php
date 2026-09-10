<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Redirect;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RedirectQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSourcePath, description: '来源路径（站内相对路径，以 / 开头）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class RedirectQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSourcePath = 'sourcePath';

    public const string getStatus = 'status';

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
