<?php

declare(strict_types=1);

namespace app\domain\system\request\systemSetting;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemSettingCreateRequest',
    required: [
        'id',
        'group',
        'key',
        'title',
        'value',
        'type',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'group', description: '配置分组:basic=基础,seo,upload', type: 'string'),
        new OA\Property(property: 'key', description: '配置键,如:site_name', type: 'string'),
        new OA\Property(property: 'title', description: '配置名称,用于后台表单展示', type: 'string'),
        new OA\Property(property: 'value', description: '配置值', type: 'string'),
        new OA\Property(property: 'type', description: '值类型:text,number,select,radio', type: 'string'),
    ]
)]
class SystemSettingCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'group' => 'require',
        'key' => 'require',
        'title' => 'require',
        'value' => 'require',
        'type' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'group.require' => '请设置配置分组:basic=基础,seo,upload',
        'key.require' => '请设置配置键,如:site_name',
        'title.require' => '请设置配置名称,用于后台表单展示',
        'value.require' => '请设置配置值',
        'type.require' => '请设置值类型:text,number,select,radio',
    ];
}
