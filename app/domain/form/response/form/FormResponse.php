<?php

declare(strict_types=1);

namespace app\domain\form\response\form;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormResponse')]
class FormResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '表单名称,如:活动报名', type: 'string')]
    private string $name;

    #[OA\Property(property: 'description', description: '表单描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'successMessage', description: '提交成功提示语', type: 'string')]
    private string $successMessage;

    #[OA\Property(property: 'startTime', description: '可提交开始时间,空=不限', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '可提交结束时间,空=不限', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'needLogin', description: '需要登录:0=否,1=是', type: 'integer')]
    private int $needLogin;

    #[OA\Property(property: 'maxSubmits', description: '每人限提次数,0=不限', type: 'integer')]
    private int $maxSubmits;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    #[OA\Property(property: 'deleteTime', description: '', type: 'string')]
    private string $deleteTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    public function setSuccessMessage(string $successMessage): void
    {
        $this->successMessage = $successMessage;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getNeedLogin(): int
    {
        return $this->needLogin;
    }

    public function setNeedLogin(int $needLogin): void
    {
        $this->needLogin = $needLogin;
    }

    public function getMaxSubmits(): int
    {
        return $this->maxSubmits;
    }

    public function setMaxSubmits(int $maxSubmits): void
    {
        $this->maxSubmits = $maxSubmits;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
