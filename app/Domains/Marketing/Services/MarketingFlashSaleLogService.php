<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingFlashSaleLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingFlashSaleLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingFlashSaleLogRepository $repository,
    ) {}

    public function getRepository(): MarketingFlashSaleLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
