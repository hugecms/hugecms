<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderQueryRequest',
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退', type: 'integer'),
        new OA\Property(property: self::getWithdrawNo, description: '提现单号', type: 'string'),
    ]
)]
class UserWithdrawOrderQueryRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getStatus = 'status';

    public const string getWithdrawNo = 'withdrawNo';

    public function rules(): array
    {
        return [
            self::getUserId => 'nullable|integer|min:1',
            self::getStatus => 'nullable|integer|in:10,20,30,40,50,60',
            self::getWithdrawNo => 'nullable|string|max:32',
        ];
    }
}
