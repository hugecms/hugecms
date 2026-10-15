<?php

declare(strict_types=1);

namespace app\domain\site\service;

use app\domain\site\repository\SiteBannerRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SiteBannerService extends CommonService implements ServiceInterface
{
    public function getRepository(): SiteBannerRepository
    {
        return SiteBannerRepository::getInstance();
    }
}
