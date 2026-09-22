<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\NavItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class NavItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly NavItemRepository $repository,
    ) {}

    public function getRepository(): NavItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
