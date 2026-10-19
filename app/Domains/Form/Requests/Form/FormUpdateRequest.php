<?php

declare(strict_types=1);

namespace App\Domains\Form\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getDescription,
        self::getSuccessMessage,
        self::getStartTime,
        self::getEndTime,
        self::getNeedLogin,
        self::getMaxSubmits,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
        self::getDeleteTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '表单名称,如:活动报名', type: 'string'),
        new OA\Property(property: self::getDescription, description: '表单描述', type: 'string'),
        new OA\Property(property: self::getSuccessMessage, description: '提交成功提示语', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '可提交开始时间,空=不限', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '可提交结束时间,空=不限', type: 'string'),
        new OA\Property(property: self::getNeedLogin, description: '需要登录:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getMaxSubmits, description: '每人限提次数,0=不限', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
        new OA\Property(property: self::getDeleteTime, description: '', type: 'string'),
    ]
)]
class FormUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getDescription = 'description';

    public const string getSuccessMessage = 'successMessage';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getNeedLogin = 'needLogin';

    public const string getMaxSubmits = 'maxSubmits';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public const string getDeleteTime = 'deleteTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getDescription => 'required',
            self::getSuccessMessage => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getNeedLogin => 'required',
            self::getMaxSubmits => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
            self::getDeleteTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置表单名称,如:活动报名',
            self::getDescription.'.required' => '请设置表单描述',
            self::getSuccessMessage.'.required' => '请设置提交成功提示语',
            self::getStartTime.'.required' => '请设置可提交开始时间,空=不限',
            self::getEndTime.'.required' => '请设置可提交结束时间,空=不限',
            self::getNeedLogin.'.required' => '请设置需要登录:0=否,1=是',
            self::getMaxSubmits.'.required' => '请设置每人限提次数,0=不限',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
            self::getDeleteTime.'.required' => '请设置',
        ];
    }
}
