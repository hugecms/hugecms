<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getWithdrawNo, description: '提现单号,应用层生成', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '打款渠道:manual=人工线下打款;微信商家转账后续扩展', type: 'string'),
        new OA\Property(property: self::getChannelOrderNo, description: '渠道打款单号,回填防重复打款', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:10=待审核,20=审核通过,30=打款中,40=成功,50=审核拒绝,60=打款失败已回退', type: 'integer'),
    ]
)]
class UserWithdrawOrderQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getWithdrawNo = 'withdrawNo';

    public const string getUserId = 'userId';

    public const string getChannel = 'channel';

    public const string getChannelOrderNo = 'channelOrderNo';

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
