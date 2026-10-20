<?php

declare(strict_types=1);

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Repositories\AdminUserRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminUserRoleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdminUserRoleRepository $repository,
    ) {}

    public function getRepository(): AdminUserRoleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
