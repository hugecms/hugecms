<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemDictItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemDictItemCreateRequest',
    required: [
        self::getDictId,
        self::getValue,
        self::getLabel,
        self::getSort,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getDictId, description: '字典类型ID', type: 'integer'),
        new OA\Property(property: self::getValue, description: '字典值', type: 'string'),
        new OA\Property(property: self::getLabel, description: '字典标签', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class SystemDictItemCreateRequest extends FormRequest
{
    public const string getDictId = 'dictId';

    public const string getValue = 'value';

    public const string getLabel = 'label';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getDictId => 'required',
            self::getValue => 'required',
            self::getLabel => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getDictId.'.required' => '请设置字典类型ID',
            self::getValue.'.required' => '请设置字典值',
            self::getLabel.'.required' => '请设置字典标签',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
