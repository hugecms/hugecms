<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserNotificationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserNotificationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserNotificationRepository $repository,
    ) {}

    public function getRepository(): UserNotificationRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
