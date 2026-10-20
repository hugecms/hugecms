<?php

declare(strict_types=1);

namespace App\Domains\System\Requests\SystemAttachment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SystemAttachmentUpdateRequest',
    required: [
        self::getId,
        self::getStorage,
        self::getFilePath,
        self::getFileName,
        self::getMimeType,
        self::getFileSize,
        self::getModule,
        self::getUserType,
        self::getUserId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getStorage, description: '存储驱动:local,oss,qiniu', type: 'string'),
        new OA\Property(property: self::getFilePath, description: '文件路径', type: 'string'),
        new OA\Property(property: self::getFileName, description: '原始文件名', type: 'string'),
        new OA\Property(property: self::getMimeType, description: 'MIME类型', type: 'string'),
        new OA\Property(property: self::getFileSize, description: '文件大小(字节)', type: 'integer'),
        new OA\Property(property: self::getModule, description: '所属模块:article,product', type: 'string'),
        new OA\Property(property: self::getUserType, description: '上传账户类型:admin,user', type: 'string'),
        new OA\Property(property: self::getUserId, description: '上传人ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class SystemAttachmentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getStorage = 'storage';

    public const string getFilePath = 'filePath';

    public const string getFileName = 'fileName';

    public const string getMimeType = 'mimeType';

    public const string getFileSize = 'fileSize';

    public const string getModule = 'module';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getStorage => 'required',
            self::getFilePath => 'required',
            self::getFileName => 'required',
            self::getMimeType => 'required',
            self::getFileSize => 'required',
            self::getModule => 'required',
            self::getUserType => 'required',
            self::getUserId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getStorage.'.required' => '请设置存储驱动:local,oss,qiniu',
            self::getFilePath.'.required' => '请设置文件路径',
            self::getFileName.'.required' => '请设置原始文件名',
            self::getMimeType.'.required' => '请设置MIME类型',
            self::getFileSize.'.required' => '请设置文件大小(字节)',
            self::getModule.'.required' => '请设置所属模块:article,product',
            self::getUserType.'.required' => '请设置上传账户类型:admin,user',
            self::getUserId.'.required' => '请设置上传人ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
