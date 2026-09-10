<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\TermRelationship;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TermRelationshipUpdateRequest',
    required: [
        self::getId,
        self::getContentId,
        self::getTermId,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '内容主表ID', type: 'integer'),
        new OA\Property(property: self::getTermId, description: '分类项ID', type: 'integer'),
        new OA\Property(property: self::getSort, description: '该内容在此分类下的自定义排序', type: 'integer'),
    ]
)]
class TermRelationshipUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

    public const string getTermId = 'termId';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getContentId => 'required',
            self::getTermId => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getContentId.'.required' => '请设置内容主表ID',
            self::getTermId.'.required' => '请设置分类项ID',
            self::getSort.'.required' => '请设置该内容在此分类下的自定义排序',
        ];
    }
}
