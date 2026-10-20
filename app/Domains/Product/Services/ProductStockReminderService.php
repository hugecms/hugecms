<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductStockReminderRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductStockReminderService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductStockReminderRepository $repository,
    ) {}

    public function getRepository(): ProductStockReminderRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
