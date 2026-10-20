<?php

declare(strict_types=1);

namespace App\Domains\Product\Services;

use App\Domains\Product\Repositories\ProductMemberPriceRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductMemberPriceService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductMemberPriceRepository $repository,
    ) {}

    public function getRepository(): ProductMemberPriceRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
