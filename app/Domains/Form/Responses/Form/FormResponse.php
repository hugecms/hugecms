<?php

declare(strict_types=1);

namespace App\Domains\Form\Responses\Form;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormResponse')]
class FormResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

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
     * 获取表单名称,如:活动报名
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置表单名称,如:活动报名
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取表单描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置表单描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取提交成功提示语
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * 设置提交成功提示语
     */
    public function setSuccessMessage(string $successMessage): void
    {
        $this->successMessage = $successMessage;
    }

    /**
     * 获取可提交开始时间,空=不限
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置可提交开始时间,空=不限
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取可提交结束时间,空=不限
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置可提交结束时间,空=不限
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取需要登录:0=否,1=是
     */
    public function getNeedLogin(): int
    {
        return $this->needLogin;
    }

    /**
     * 设置需要登录:0=否,1=是
     */
    public function setNeedLogin(int $needLogin): void
    {
        $this->needLogin = $needLogin;
    }

    /**
     * 获取每人限提次数,0=不限
     */
    public function getMaxSubmits(): int
    {
        return $this->maxSubmits;
    }

    /**
     * 设置每人限提次数,0=不限
     */
    public function setMaxSubmits(int $maxSubmits): void
    {
        $this->maxSubmits = $maxSubmits;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * 获取
     */
    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    /**
     * 设置
     */
    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
