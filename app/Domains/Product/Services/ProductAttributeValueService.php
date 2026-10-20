<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductAttributeValueRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductAttributeValueService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductAttributeValueRepository $repository,
    ) {}

    public function getRepository(): ProductAttributeValueRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
