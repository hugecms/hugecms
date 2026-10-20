<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTagUpdateRequest',
    required: [
        self::getId,
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '标签名', type: 'string'),
    ]
)]
class ContentTagUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置标签名',
        ];
    }
}
