<?php

declare(strict_types=1);

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Repositories\AdminPermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminPermissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdminPermissionRepository $repository,
    ) {}

    public function getRepository(): AdminPermissionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
