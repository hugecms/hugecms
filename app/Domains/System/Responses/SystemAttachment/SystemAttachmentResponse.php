<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemAttachment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemAttachmentResponse')]
class SystemAttachmentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'storage', description: '存储驱动:local,oss,qiniu', type: 'string')]
    private string $storage;

    #[OA\Property(property: 'filePath', description: '文件路径', type: 'string')]
    private string $filePath;

    #[OA\Property(property: 'fileName', description: '原始文件名', type: 'string')]
    private string $fileName;

    #[OA\Property(property: 'mimeType', description: 'MIME类型', type: 'string')]
    private string $mimeType;

    #[OA\Property(property: 'fileSize', description: '文件大小(字节)', type: 'integer')]
    private int $fileSize;

    #[OA\Property(property: 'module', description: '所属模块:article,product', type: 'string')]
    private string $module;

    #[OA\Property(property: 'userType', description: '上传账户类型:admin,user', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '上传人ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

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
     * 获取存储驱动:local,oss,qiniu
     */
    public function getStorage(): string
    {
        return $this->storage;
    }

    /**
     * 设置存储驱动:local,oss,qiniu
     */
    public function setStorage(string $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * 获取文件路径
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * 设置文件路径
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
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
     * 获取MIME类型
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * 设置MIME类型
     */
    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * 获取文件大小(字节)
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * 设置文件大小(字节)
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * 获取所属模块:article,product
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * 设置所属模块:article,product
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * 获取上传账户类型:admin,user
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置上传账户类型:admin,user
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * 获取上传人ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置上传人ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
