<?php

declare(strict_types=1);

namespace app\domain\user\service;

use app\domain\user\repository\UserSmsCodeRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class UserSmsCodeService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserSmsCodeRepository
    {
        return UserSmsCodeRepository::getInstance();
    }
}
