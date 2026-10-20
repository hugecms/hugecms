<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTagCreateRequest',
    required: [
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '标签名', type: 'string'),
    ]
)]
class ContentTagCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public function rules(): array
    {
        return [
            self::getName => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置标签名',
        ];
    }
}
