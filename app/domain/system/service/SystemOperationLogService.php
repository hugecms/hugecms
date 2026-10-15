<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemOperationLogRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemOperationLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemOperationLogRepository
    {
        return SystemOperationLogRepository::getInstance();
    }
}
