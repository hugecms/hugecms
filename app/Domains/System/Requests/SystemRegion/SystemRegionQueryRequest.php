<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRegionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=省级', type: 'integer'),
    ]
)]
class SystemRegionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

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
