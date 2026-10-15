<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentCategoryRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentCategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentCategoryRepository
    {
        return ContentCategoryRepository::getInstance();
    }
}
