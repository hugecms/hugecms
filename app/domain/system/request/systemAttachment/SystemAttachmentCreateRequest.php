<?php

declare(strict_types=1);

namespace app\domain\system\request\systemAttachment;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemAttachmentCreateRequest',
    required: [
        'id',
        'storage',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'module',
        'user_type',
        'user_id',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'storage', description: '存储驱动:local,oss,qiniu', type: 'string'),
        new OA\Property(property: 'file_path', description: '文件路径', type: 'string'),
        new OA\Property(property: 'file_name', description: '原始文件名', type: 'string'),
        new OA\Property(property: 'mime_type', description: 'MIME类型', type: 'string'),
        new OA\Property(property: 'file_size', description: '文件大小(字节)', type: 'integer'),
        new OA\Property(property: 'module', description: '所属模块:article,product', type: 'string'),
        new OA\Property(property: 'user_type', description: '上传账户类型:admin,user', type: 'string'),
        new OA\Property(property: 'user_id', description: '上传人ID', type: 'integer'),
    ]
)]
class SystemAttachmentCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'storage' => 'require',
        'file_path' => 'require',
        'file_name' => 'require',
        'mime_type' => 'require',
        'file_size' => 'require',
        'module' => 'require',
        'user_type' => 'require',
        'user_id' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'storage.require' => '请设置存储驱动:local,oss,qiniu',
        'file_path.require' => '请设置文件路径',
        'file_name.require' => '请设置原始文件名',
        'mime_type.require' => '请设置MIME类型',
        'file_size.require' => '请设置文件大小(字节)',
        'module.require' => '请设置所属模块:article,product',
        'user_type.require' => '请设置上传账户类型:admin,user',
        'user_id.require' => '请设置上传人ID',
    ];
}
