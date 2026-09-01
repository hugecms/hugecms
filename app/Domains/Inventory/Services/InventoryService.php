<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Services;

use App\Domains\Inventory\Repositories\InventoryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class InventoryService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly InventoryRepository $repository,
    ) {}

    public function getRepository(): InventoryRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
