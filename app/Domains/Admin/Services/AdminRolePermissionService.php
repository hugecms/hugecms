<?php

declare(strict_types=1);

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Repositories\AdminRolePermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminRolePermissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdminRolePermissionRepository $repository,
    ) {}

    public function getRepository(): AdminRolePermissionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
