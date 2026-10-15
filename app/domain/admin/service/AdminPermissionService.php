<?php

declare(strict_types=1);

namespace app\domain\admin\service;

use app\domain\admin\repository\AdminPermissionRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class AdminPermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminPermissionRepository
    {
        return AdminPermissionRepository::getInstance();
    }
}
