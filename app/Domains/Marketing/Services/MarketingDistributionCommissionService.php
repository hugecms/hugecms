<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionCommissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingDistributionCommissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributionCommissionRepository $repository,
    ) {}

    public function getRepository(): MarketingDistributionCommissionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
