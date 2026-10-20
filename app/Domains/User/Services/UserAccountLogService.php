<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserAccountLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAccountLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserAccountLogRepository $repository,
    ) {}

    public function getRepository(): UserAccountLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
