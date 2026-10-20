<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionUserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingDistributionUserService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributionUserRepository $repository,
    ) {}

    public function getRepository(): MarketingDistributionUserRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
