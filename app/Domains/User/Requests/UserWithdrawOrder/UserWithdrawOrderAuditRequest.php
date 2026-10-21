<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserWithdrawOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserWithdrawOrderAuditRequest',
    required: [self::getId],
    properties: [
        new OA\Property(property: self::getId, description: '提现单ID', type: 'integer'),
        new OA\Property(property: self::getPass, description: '是否通过:审核接口用,true=通过,false=拒绝', type: 'boolean'),
        new OA\Property(property: self::getAdminNote, description: '审核备注', type: 'string'),
    ]
)]
class UserWithdrawOrderAuditRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPass = 'pass';

    public const string getAdminNote = 'adminNote';

    public function rules(): array
    {
        return [
            self::getId => 'required|integer|min:1',
            self::getPass => 'nullable|boolean',
            self::getAdminNote => 'nullable|string|max:255',
        ];
    }
}
