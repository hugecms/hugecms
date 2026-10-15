<?php

declare(strict_types=1);

namespace app\domain\system\entity;

use Juling\Foundation\Support\ArrayHelper;

class SystemAttachmentEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getStorage = 'storage'; // 存储驱动:local,oss,qiniu

    public const string getFilePath = 'file_path'; // 文件路径

    public const string getFileName = 'file_name'; // 原始文件名

    public const string getMimeType = 'mime_type'; // MIME类型

    public const string getFileSize = 'file_size'; // 文件大小(字节)

    public const string getModule = 'module'; // 所属模块:article,product

    public const string getUserType = 'user_type'; // 上传账户类型:admin,user

    public const string getUserId = 'user_id'; // 上传人ID

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private string $storage;

    private string $filePath;

    private string $fileName;

    private string $mimeType;

    private int $fileSize;

    private string $module;

    private string $userType;

    private int $userId;

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
