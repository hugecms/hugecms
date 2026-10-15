<?php

declare(strict_types=1);

namespace app\domain\user\service;

use app\domain\user\repository\UserNotificationRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class UserNotificationService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserNotificationRepository
    {
        return UserNotificationRepository::getInstance();
    }
}
