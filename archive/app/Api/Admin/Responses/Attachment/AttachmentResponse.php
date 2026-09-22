<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Attachment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AttachmentResponse')]
class AttachmentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'uploaderId', description: '上传者ID', type: 'integer')]
    private int $uploaderId;

    #[OA\Property(property: 'fileName', description: '原始文件名', type: 'string')]
    private string $fileName;

    #[OA\Property(property: 'filePath', description: '物理存储相对路径', type: 'string')]
    private string $filePath;

    #[OA\Property(property: 'storageDriver', description: '存储驱动：local/oss/cos/s3', type: 'string')]
    private string $storageDriver;

    #[OA\Property(property: 'storageBucket', description: '存储桶名称（仅云存储有效）', type: 'string')]
    private string $storageBucket;

    #[OA\Property(property: 'cdnUrl', description: 'CDN加速访问URL', type: 'string')]
    private string $cdnUrl;

    #[OA\Property(property: 'fileSize', description: '文件大小（字节）', type: 'integer')]
    private int $fileSize;

    #[OA\Property(property: 'mimeType', description: 'MIME类型（如：image/jpeg）', type: 'string')]
    private string $mimeType;

    #[OA\Property(property: 'width', description: '图片宽度（仅图片）', type: 'integer')]
    private int $width;

    #[OA\Property(property: 'height', description: '图片高度（仅图片）', type: 'integer')]
    private int $height;

    #[OA\Property(property: 'altText', description: 'SEO替代文本', type: 'string')]
    private string $altText;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取上传者ID
     */
    public function getUploaderId(): int
    {
        return $this->uploaderId;
    }

    /**
     * 设置上传者ID
     */
    public function setUploaderId(int $uploaderId): void
    {
        $this->uploaderId = $uploaderId;
    }

    /**
     * 获取原始文件名
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * 设置原始文件名
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * 获取物理存储相对路径
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * 设置物理存储相对路径
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * 获取存储驱动：local/oss/cos/s3
     */
    public function getStorageDriver(): string
    {
        return $this->storageDriver;
    }

    /**
     * 设置存储驱动：local/oss/cos/s3
     */
    public function setStorageDriver(string $storageDriver): void
    {
        $this->storageDriver = $storageDriver;
    }

    /**
     * 获取存储桶名称（仅云存储有效）
     */
    public function getStorageBucket(): string
    {
        return $this->storageBucket;
    }

    /**
     * 设置存储桶名称（仅云存储有效）
     */
    public function setStorageBucket(string $storageBucket): void
    {
        $this->storageBucket = $storageBucket;
    }

    /**
     * 获取CDN加速访问URL
     */
    public function getCdnUrl(): string
    {
        return $this->cdnUrl;
    }

    /**
     * 设置CDN加速访问URL
     */
    public function setCdnUrl(string $cdnUrl): void
    {
        $this->cdnUrl = $cdnUrl;
    }

    /**
     * 获取文件大小（字节）
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * 设置文件大小（字节）
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * 获取MIME类型（如：image/jpeg）
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * 设置MIME类型（如：image/jpeg）
     */
    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * 获取图片宽度（仅图片）
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * 设置图片宽度（仅图片）
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * 获取图片高度（仅图片）
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * 设置图片高度（仅图片）
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * 获取SEO替代文本
     */
    public function getAltText(): string
    {
        return $this->altText;
    }

    /**
     * 设置SEO替代文本
     */
    public function setAltText(string $altText): void
    {
        $this->altText = $altText;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
