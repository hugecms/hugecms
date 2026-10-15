<?php

declare(strict_types=1);

namespace app\domain\user\service;

use app\domain\user\repository\UserSocialRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class UserSocialService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserSocialRepository
    {
        return UserSocialRepository::getInstance();
    }
}
