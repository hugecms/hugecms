<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatUserCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class WechatUserCreateRequest extends FormRequest
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
