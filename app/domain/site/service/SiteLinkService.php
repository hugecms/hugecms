<?php

declare(strict_types=1);

namespace app\domain\site\service;

use app\domain\site\repository\SiteLinkRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SiteLinkService extends CommonService implements ServiceInterface
{
    public function getRepository(): SiteLinkRepository
    {
        return SiteLinkRepository::getInstance();
    }
}
