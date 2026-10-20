<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductStockLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductStockLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductStockLogRepository $repository,
    ) {}

    public function getRepository(): ProductStockLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
