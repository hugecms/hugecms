<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentPageRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentPageService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentPageRepository
    {
        return ContentPageRepository::getInstance();
    }
}
