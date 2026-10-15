<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentArticleContentRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentArticleContentService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentArticleContentRepository
    {
        return ContentArticleContentRepository::getInstance();
    }
}
