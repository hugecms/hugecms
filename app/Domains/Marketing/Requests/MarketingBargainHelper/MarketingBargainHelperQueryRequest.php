<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainHelper;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainHelperQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRecordId, description: '砍价单ID', type: 'integer'),
        new OA\Property(property: self::getHelperUserId, description: '帮砍人用户ID', type: 'integer'),
    ]
)]
class MarketingBargainHelperQueryRequest extends FormRequest
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
