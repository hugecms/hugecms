<?php

declare(strict_types=1);

namespace app\domain\system\response\systemAttachment;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemAttachmentResponse')]
class SystemAttachmentResponse
{
    use ArrayHelper;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStorage(): string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): void
    {
        $this->storage = $storage;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
