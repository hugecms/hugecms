<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductVolumePriceRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductVolumePriceService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductVolumePriceRepository $repository,
    ) {}

    public function getRepository(): ProductVolumePriceRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
