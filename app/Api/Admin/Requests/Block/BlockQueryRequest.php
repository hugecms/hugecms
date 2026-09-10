<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BlockQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getBlockType, description: '区块类型：header/footer/banner/content/sidebar/custom', type: 'string'),
        new OA\Property(property: self::getIsGlobal, description: '是否全局区块（全站复用）：1是，0否', type: 'integer'),
    ]
)]
class BlockQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getBlockType = 'blockType';

    public const string getIsGlobal = 'isGlobal';

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
