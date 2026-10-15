<?php

declare(strict_types=1);

namespace app\domain\user\service;

use app\domain\user\repository\UserFavoriteRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class UserFavoriteService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserFavoriteRepository
    {
        return UserFavoriteRepository::getInstance();
    }
}
