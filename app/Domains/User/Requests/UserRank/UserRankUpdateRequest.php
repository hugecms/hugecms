<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserRank;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRankUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getMinPoints,
        self::getMaxPoints,
        self::getDiscount,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '等级名称,如:普通会员', type: 'string'),
        new OA\Property(property: self::getMinPoints, description: '升级所需最低积分', type: 'integer'),
        new OA\Property(property: self::getMaxPoints, description: '积分上限,0=不限(最高级)', type: 'integer'),
        new OA\Property(property: self::getDiscount, description: '会员折扣,100=原价,95=95折', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class UserRankUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getMinPoints = 'minPoints';

    public const string getMaxPoints = 'maxPoints';

    public const string getDiscount = 'discount';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getMinPoints => 'required',
            self::getMaxPoints => 'required',
            self::getDiscount => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置等级名称,如:普通会员',
            self::getMinPoints.'.required' => '请设置升级所需最低积分',
            self::getMaxPoints.'.required' => '请设置积分上限,0=不限(最高级)',
            self::getDiscount.'.required' => '请设置会员折扣,100=原价,95=95折',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
