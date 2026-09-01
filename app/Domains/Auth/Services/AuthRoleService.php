<?php

declare(strict_types=1);

namespace App\Domains\Auth\Services;

use App\Domains\Auth\Repositories\AuthRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AuthRoleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AuthRoleRepository $repository,
    ) {}

    public function getRepository(): AuthRoleRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
