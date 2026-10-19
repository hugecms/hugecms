<?php

declare(strict_types=1);

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Repositories\AdminUserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminUserService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdminUserRepository $repository,
    ) {}

    public function getRepository(): AdminUserRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
