<?php

declare(strict_types=1);

namespace app\domain\site\request\siteBanner;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SiteBannerCreateRequest',
    required: [
        'id',
        'position',
        'title',
        'image',
        'link',
        'description',
        'sort',
        'status',
        'start_time',
        'end_time',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'position', description: '广告位标识,如:home_top', type: 'string'),
        new OA\Property(property: 'title', description: '标题', type: 'string'),
        new OA\Property(property: 'image', description: '图片地址', type: 'string'),
        new OA\Property(property: 'link', description: '跳转链接', type: 'string'),
        new OA\Property(property: 'description', description: '描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: 'start_time', description: '投放开始时间', type: 'string'),
        new OA\Property(property: 'end_time', description: '投放结束时间', type: 'string'),
    ]
)]
class SiteBannerCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'position' => 'require',
        'title' => 'require',
        'image' => 'require',
        'link' => 'require',
        'description' => 'require',
        'sort' => 'require',
        'status' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'position.require' => '请设置广告位标识,如:home_top',
        'title.require' => '请设置标题',
        'image.require' => '请设置图片地址',
        'link.require' => '请设置跳转链接',
        'description.require' => '请设置描述',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
        'start_time.require' => '请设置投放开始时间',
        'end_time.require' => '请设置投放结束时间',
    ];
}
