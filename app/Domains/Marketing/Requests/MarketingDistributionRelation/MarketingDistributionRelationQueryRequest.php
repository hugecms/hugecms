<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingDistributionRelation;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingDistributionRelationQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '被绑定用户ID(下单人)', type: 'integer'),
        new OA\Property(property: self::getDistributorId, description: '分销员用户ID', type: 'integer'),
    ]
)]
class MarketingDistributionRelationQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getDistributorId = 'distributorId';

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
