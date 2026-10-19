<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemRegionUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getName,
        self::getLevel,
        self::getCode,
        self::getSort,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=省级', type: 'integer'),
        new OA\Property(property: self::getName, description: '区域名称', type: 'string'),
        new OA\Property(property: self::getLevel, description: '层级:1=省,2=市,3=区县', type: 'integer'),
        new OA\Property(property: self::getCode, description: '行政区划码', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class SystemRegionUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getLevel = 'level';

    public const string getCode = 'code';

    public const string getSort = 'sort';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getName => 'required',
            self::getLevel => 'required',
            self::getCode => 'required',
            self::getSort => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父级ID,0=省级',
            self::getName.'.required' => '请设置区域名称',
            self::getLevel.'.required' => '请设置层级:1=省,2=市,3=区县',
            self::getCode.'.required' => '请设置行政区划码',
            self::getSort.'.required' => '请设置排序',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
