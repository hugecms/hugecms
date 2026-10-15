<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentTagRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentTagService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRepository
    {
        return ContentTagRepository::getInstance();
    }
}
