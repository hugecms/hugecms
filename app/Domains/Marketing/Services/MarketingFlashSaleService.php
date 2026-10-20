<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingFlashSaleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingFlashSaleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingFlashSaleRepository $repository,
    ) {}

    public function getRepository(): MarketingFlashSaleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
