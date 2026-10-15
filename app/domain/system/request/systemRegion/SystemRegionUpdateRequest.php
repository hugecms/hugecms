<?php

declare(strict_types=1);

namespace app\domain\system\request\systemRegion;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemRegionUpdateRequest',
    required: [
        'id',
        'parent_id',
        'name',
        'level',
        'code',
        'sort',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID,0=省级', type: 'integer'),
        new OA\Property(property: 'name', description: '区域名称', type: 'string'),
        new OA\Property(property: 'level', description: '层级:1=省,2=市,3=区县', type: 'integer'),
        new OA\Property(property: 'code', description: '行政区划码', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
    ]
)]
class SystemRegionUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'parent_id' => 'require',
        'name' => 'require',
        'level' => 'require',
        'code' => 'require',
        'sort' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'parent_id.require' => '请设置父级ID,0=省级',
        'name.require' => '请设置区域名称',
        'level.require' => '请设置层级:1=省,2=市,3=区县',
        'code.require' => '请设置行政区划码',
        'sort.require' => '请设置排序',
    ];
}
