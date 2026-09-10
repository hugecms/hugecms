<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NavMenuRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class NavMenuService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly NavMenuRepository $repository,
    ) {}

    public function getRepository(): NavMenuRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
