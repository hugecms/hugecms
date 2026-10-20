<?php

declare(strict_types=1);

namespace App\Domains\Chat\Requests\ChatFaq;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ChatFaqQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategory, description: '分类:pre_sales=售前,logistics=物流,after_sales=售后,account=账户', type: 'string'),
    ]
)]
class ChatFaqQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategory = 'category';

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
