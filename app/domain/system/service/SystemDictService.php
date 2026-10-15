<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemDictRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemDictService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemDictRepository
    {
        return SystemDictRepository::getInstance();
    }
}
