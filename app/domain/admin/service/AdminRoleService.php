<?php

declare(strict_types=1);

namespace app\domain\admin\service;

use app\domain\admin\repository\AdminRoleRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class AdminRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminRoleRepository
    {
        return AdminRoleRepository::getInstance();
    }
}
