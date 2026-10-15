<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemRegionRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemRegionService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemRegionRepository
    {
        return SystemRegionRepository::getInstance();
    }
}
