<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserPointLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserPointLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserPointLogRepository $repository,
    ) {}

    public function getRepository(): UserPointLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
