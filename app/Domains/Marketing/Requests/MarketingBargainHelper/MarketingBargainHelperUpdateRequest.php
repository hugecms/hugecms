<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainHelper;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainHelperUpdateRequest',
    required: [
        self::getId,
        self::getRecordId,
        self::getHelperUserId,
        self::getCutAmount,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRecordId, description: '砍价单ID', type: 'integer'),
        new OA\Property(property: self::getHelperUserId, description: '帮砍人用户ID', type: 'integer'),
        new OA\Property(property: self::getCutAmount, description: '本刀砍掉金额(与record条件更新同事务)', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '帮砍时间', type: 'string'),
    ]
)]
class MarketingBargainHelperUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRecordId = 'recordId';

    public const string getHelperUserId = 'helperUserId';

    public const string getCutAmount = 'cutAmount';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRecordId => 'required',
            self::getHelperUserId => 'required',
            self::getCutAmount => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRecordId.'.required' => '请设置砍价单ID',
            self::getHelperUserId.'.required' => '请设置帮砍人用户ID',
            self::getCutAmount.'.required' => '请设置本刀砍掉金额(与record条件更新同事务)',
            self::getCreateTime.'.required' => '请设置帮砍时间',
        ];
    }
}
