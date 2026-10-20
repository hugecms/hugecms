<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionRelationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingDistributionRelationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributionRelationRepository $repository,
    ) {}

    public function getRepository(): MarketingDistributionRelationRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
