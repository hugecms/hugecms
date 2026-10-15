<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentArticleRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentArticleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentArticleRepository
    {
        return ContentArticleRepository::getInstance();
    }
}
