<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemLoginLogRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemLoginLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemLoginLogRepository
    {
        return SystemLoginLogRepository::getInstance();
    }
}
