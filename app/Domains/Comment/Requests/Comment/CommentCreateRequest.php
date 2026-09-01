<?php

declare(strict_types=1);

namespace App\Domains\Comment\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommentCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class CommentCreateRequest extends FormRequest
{
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
