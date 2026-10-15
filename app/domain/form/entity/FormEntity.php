<?php

declare(strict_types=1);

namespace app\domain\form\entity;

use Juling\Foundation\Support\ArrayHelper;

class FormEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 表单名称,如:活动报名

    public const string getDescription = 'description'; // 表单描述

    public const string getSuccessMessage = 'success_message'; // 提交成功提示语

    public const string getStartTime = 'start_time'; // 可提交开始时间,空=不限

    public const string getEndTime = 'end_time'; // 可提交结束时间,空=不限

    public const string getNeedLogin = 'need_login'; // 需要登录:0=否,1=是

    public const string getMaxSubmits = 'max_submits'; // 每人限提次数,0=不限

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    public const string getDeleteTime = 'delete_time'; //

    private int $id;

    private string $name;

    private string $description;

    private string $successMessage;

    private string $startTime;

    private string $endTime;

    private int $needLogin;

    private int $maxSubmits;

    private int $status;

    private string $createTime;

    private string $updateTime;

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
