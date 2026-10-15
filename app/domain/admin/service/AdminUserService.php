<?php

declare(strict_types=1);

namespace app\domain\admin\service;

use app\domain\admin\repository\AdminUserRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class AdminUserService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminUserRepository
    {
        return AdminUserRepository::getInstance();
    }
}
