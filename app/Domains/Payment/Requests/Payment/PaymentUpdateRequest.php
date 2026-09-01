<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentUpdateRequest',
    required: [
        self::getId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
    ]
)]
class PaymentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public function rules(): array
    {
        return [
            self::getId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
        ];
    }
}
