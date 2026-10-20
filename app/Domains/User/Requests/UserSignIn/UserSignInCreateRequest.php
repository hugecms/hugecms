<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSignIn;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSignInCreateRequest',
    required: [
        self::getUserId,
        self::getSignDate,
        self::getContinuousDays,
        self::getPoints,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getSignDate, description: '签到日期', type: 'string'),
        new OA\Property(property: self::getContinuousDays, description: '连续签到天数', type: 'integer'),
        new OA\Property(property: self::getPoints, description: '本次获得积分', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserSignInCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getSignDate = 'signDate';

    public const string getContinuousDays = 'continuousDays';

    public const string getPoints = 'points';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getSignDate => 'required',
            self::getContinuousDays => 'required',
            self::getPoints => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getSignDate.'.required' => '请设置签到日期',
            self::getContinuousDays.'.required' => '请设置连续签到天数',
            self::getPoints.'.required' => '请设置本次获得积分',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
