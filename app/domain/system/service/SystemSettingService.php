<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemSettingRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemSettingService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemSettingRepository
    {
        return SystemSettingRepository::getInstance();
    }
}
