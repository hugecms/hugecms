<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\TermRelationship;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TermRelationshipQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTermId, description: '分类项ID', type: 'integer'),
    ]
)]
class TermRelationshipQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTermId = 'termId';

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
