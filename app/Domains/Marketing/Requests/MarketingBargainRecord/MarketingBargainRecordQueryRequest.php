<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainRecordQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getBargainNo, description: '砍价单号,全局唯一', type: 'string'),
        new OA\Property(property: self::getUserId, description: '发起人用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=砍价中,2=到底价待下单,3=已下单,4=超时失败,5=已取消', type: 'integer'),
        new OA\Property(property: self::getExpireTime, description: '砍价截止时间,超时扫描依据', type: 'string'),
        new OA\Property(property: self::getOrderNo, description: '成交订单号,下单后回填;NULL=未下单,NULL不参与唯一约束', type: 'string'),
    ]
)]
class MarketingBargainRecordQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getBargainNo = 'bargainNo';

    public const string getUserId = 'userId';

    public const string getStatus = 'status';

    public const string getExpireTime = 'expireTime';

    public const string getOrderNo = 'orderNo';

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
