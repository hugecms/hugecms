<?php

namespace App\Enums;

enum MenuItemType: string
{
    case Custom = 'custom';
    case Category = 'category';
    case Page = 'page';
    case Article = 'article';

    public function getLabel(): string
    {
        return match ($this) {
            self::Custom => '自定义链接',
            self::Category => '分类',
            self::Page => '页面',
            self::Article => '文章',
        };
    }
}
