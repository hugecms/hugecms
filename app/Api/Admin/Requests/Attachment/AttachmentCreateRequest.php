<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Attachment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AttachmentCreateRequest',
    required: [
        self::getUploaderId,
        self::getFileName,
        self::getFilePath,
        self::getStorageDriver,
        self::getStorageBucket,
        self::getCdnUrl,
        self::getFileSize,
        self::getMimeType,
        self::getWidth,
        self::getHeight,
        self::getAltText,
        self::getSort,
    ],
    properties: [
        new OA\Property(property: self::getUploaderId, description: '上传者ID', type: 'integer'),
        new OA\Property(property: self::getFileName, description: '原始文件名', type: 'string'),
        new OA\Property(property: self::getFilePath, description: '物理存储相对路径', type: 'string'),
        new OA\Property(property: self::getStorageDriver, description: '存储驱动：local/oss/cos/s3', type: 'string'),
        new OA\Property(property: self::getStorageBucket, description: '存储桶名称（仅云存储有效）', type: 'string'),
        new OA\Property(property: self::getCdnUrl, description: 'CDN加速访问URL', type: 'string'),
        new OA\Property(property: self::getFileSize, description: '文件大小（字节）', type: 'integer'),
        new OA\Property(property: self::getMimeType, description: 'MIME类型（如：image/jpeg）', type: 'string'),
        new OA\Property(property: self::getWidth, description: '图片宽度（仅图片）', type: 'integer'),
        new OA\Property(property: self::getHeight, description: '图片高度（仅图片）', type: 'integer'),
        new OA\Property(property: self::getAltText, description: 'SEO替代文本', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
    ]
)]
class AttachmentCreateRequest extends FormRequest
{
    public const string getUploaderId = 'uploaderId';

    public const string getFileName = 'fileName';

    public const string getFilePath = 'filePath';

    public const string getStorageDriver = 'storageDriver';

    public const string getStorageBucket = 'storageBucket';

    public const string getCdnUrl = 'cdnUrl';

    public const string getFileSize = 'fileSize';

    public const string getMimeType = 'mimeType';

    public const string getWidth = 'width';

    public const string getHeight = 'height';

    public const string getAltText = 'altText';

    public const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getUploaderId => 'required',
            self::getFileName => 'required',
            self::getFilePath => 'required',
            self::getStorageDriver => 'required',
            self::getStorageBucket => 'required',
            self::getCdnUrl => 'required',
            self::getFileSize => 'required',
            self::getMimeType => 'required',
            self::getWidth => 'required',
            self::getHeight => 'required',
            self::getAltText => 'required',
            self::getSort => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUploaderId.'.required' => '请设置上传者ID',
            self::getFileName.'.required' => '请设置原始文件名',
            self::getFilePath.'.required' => '请设置物理存储相对路径',
            self::getStorageDriver.'.required' => '请设置存储驱动：local/oss/cos/s3',
            self::getStorageBucket.'.required' => '请设置存储桶名称（仅云存储有效）',
            self::getCdnUrl.'.required' => '请设置CDN加速访问URL',
            self::getFileSize.'.required' => '请设置文件大小（字节）',
            self::getMimeType.'.required' => '请设置MIME类型（如：image/jpeg）',
            self::getWidth.'.required' => '请设置图片宽度（仅图片）',
            self::getHeight.'.required' => '请设置图片高度（仅图片）',
            self::getAltText.'.required' => '请设置SEO替代文本',
            self::getSort.'.required' => '请设置排序',
        ];
    }
}
