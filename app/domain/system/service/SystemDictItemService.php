<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemDictItemRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemDictItemService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemDictItemRepository
    {
        return SystemDictItemRepository::getInstance();
    }
}
