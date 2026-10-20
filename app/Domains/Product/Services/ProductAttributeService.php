<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductAttributeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductAttributeService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductAttributeRepository $repository,
    ) {}

    public function getRepository(): ProductAttributeRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
