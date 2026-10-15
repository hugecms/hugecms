<?php

declare(strict_types=1);

namespace app\domain\user\service;

use app\domain\user\repository\UserProfileRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class UserProfileService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserProfileRepository
    {
        return UserProfileRepository::getInstance();
    }
}
