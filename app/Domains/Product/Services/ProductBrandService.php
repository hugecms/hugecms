<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductBrandRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductBrandService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductBrandRepository $repository,
    ) {}

    public function getRepository(): ProductBrandRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
