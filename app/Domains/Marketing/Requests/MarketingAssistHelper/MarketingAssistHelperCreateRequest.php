<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingAssistHelper;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingAssistHelperCreateRequest',
    required: [
        self::getRecordId,
        self::getHelperUserId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getRecordId, description: '助力参与记录ID', type: 'integer'),
        new OA\Property(property: self::getHelperUserId, description: '助力人用户ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '助力时间', type: 'string'),
    ]
)]
class MarketingAssistHelperCreateRequest extends FormRequest
{
    public const string getRecordId = 'recordId';

    public const string getHelperUserId = 'helperUserId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getRecordId => 'required',
            self::getHelperUserId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecordId.'.required' => '请设置助力参与记录ID',
            self::getHelperUserId.'.required' => '请设置助力人用户ID',
            self::getCreateTime.'.required' => '请设置助力时间',
        ];
    }
}
