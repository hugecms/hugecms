<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingShareRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingShareRecordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSharerUserId, description: '分享者用户ID', type: 'integer'),
        new OA\Property(property: self::getSpuId, description: '分享对象商品SPU ID,NULL=整店/页面分享', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '分享时间,归因窗口起点', type: 'string'),
    ]
)]
class MarketingShareRecordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSharerUserId = 'sharerUserId';

    public const string getSpuId = 'spuId';

    public const string getCreateTime = 'createTime';

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
