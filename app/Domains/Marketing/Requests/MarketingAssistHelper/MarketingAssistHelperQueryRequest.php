<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingAssistHelper;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingAssistHelperQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRecordId, description: '助力参与记录ID', type: 'integer'),
        new OA\Property(property: self::getHelperUserId, description: '助力人用户ID', type: 'integer'),
    ]
)]
class MarketingAssistHelperQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRecordId = 'recordId';

    public const string getHelperUserId = 'helperUserId';

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
