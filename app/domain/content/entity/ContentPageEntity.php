<?php

declare(strict_types=1);

namespace app\domain\content\entity;

use Juling\Foundation\Support\ArrayHelper;

class ContentPageEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getTitle = 'title'; // 页面标题

    public const string getSlug = 'slug'; // 页面标识,如:about

    public const string getContent = 'content'; // 页面内容

    public const string getSeoTitle = 'seo_title'; // SEO标题

    public const string getSeoKeywords = 'seo_keywords'; // SEO关键词

    public const string getSeoDescription = 'seo_description'; // SEO描述

    public const string getStatus = 'status'; // 状态:0=隐藏,1=显示

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private string $title;

    private string $slug;

    private string $content;

    private string $seoTitle;

    private string $seoKeywords;

    private string $seoDescription;

    private int $status;

    private string $createTime;

    private string $updateTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    public function getSeoKeywords(): string
    {
        return $this->seoKeywords;
    }

    public function setSeoKeywords(string $seoKeywords): void
    {
        $this->seoKeywords = $seoKeywords;
    }

    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
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
}
