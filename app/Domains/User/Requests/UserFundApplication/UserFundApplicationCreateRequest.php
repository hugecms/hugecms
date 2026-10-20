<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserFundApplication;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFundApplicationCreateRequest',
    required: [
        self::getUserId,
        self::getType,
        self::getAmount,
        self::getStatus,
        self::getUserNote,
        self::getAdminNote,
        self::getPaidAt,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型:recharge=充值,withdraw=提现', type: 'string'),
        new OA\Property(property: self::getAmount, description: '申请金额', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=待审核,1=已到账,2=已拒绝', type: 'integer'),
        new OA\Property(property: self::getUserNote, description: '用户备注', type: 'string'),
        new OA\Property(property: self::getAdminNote, description: '管理员备注', type: 'string'),
        new OA\Property(property: self::getPaidAt, description: '到账时间', type: 'string'),
    ]
)]
class UserFundApplicationCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getType = 'type';

    public const string getAmount = 'amount';

    public const string getStatus = 'status';

    public const string getUserNote = 'userNote';

    public const string getAdminNote = 'adminNote';

    public const string getPaidAt = 'paidAt';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getType => 'required',
            self::getAmount => 'required',
            self::getStatus => 'required',
            self::getUserNote => 'required',
            self::getAdminNote => 'required',
            self::getPaidAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getType.'.required' => '请设置类型:recharge=充值,withdraw=提现',
            self::getAmount.'.required' => '请设置申请金额',
            self::getStatus.'.required' => '请设置状态:0=待审核,1=已到账,2=已拒绝',
            self::getUserNote.'.required' => '请设置用户备注',
            self::getAdminNote.'.required' => '请设置管理员备注',
            self::getPaidAt.'.required' => '请设置到账时间',
        ];
    }
}
