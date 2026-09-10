<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '投放结束时间（NULL表示永久，过期由时间判断）', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0停用，1启用', type: 'integer'),
    ]
)]
class AdQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getEndTime = 'endTime';

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
