<?php

declare(strict_types=1);

namespace App\Domains\Admin\Services;

use App\Domains\Admin\Repositories\AdminRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminRoleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdminRoleRepository $repository,
    ) {}

    public function getRepository(): AdminRoleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
