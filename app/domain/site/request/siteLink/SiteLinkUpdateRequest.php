<?php

declare(strict_types=1);

namespace app\domain\site\request\siteLink;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SiteLinkUpdateRequest',
    required: [
        'id',
        'name',
        'url',
        'logo',
        'description',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '站点名称', type: 'string'),
        new OA\Property(property: 'url', description: '链接地址', type: 'string'),
        new OA\Property(property: 'logo', description: '站点LOGO', type: 'string'),
        new OA\Property(property: 'description', description: '描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SiteLinkUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'url' => 'require',
        'logo' => 'require',
        'description' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置站点名称',
        'url.require' => '请设置链接地址',
        'logo.require' => '请设置站点LOGO',
        'description.require' => '请设置描述',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
