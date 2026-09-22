<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PermissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PermissionRepository $repository,
    ) {}

    public function getRepository(): PermissionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
