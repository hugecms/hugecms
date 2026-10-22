<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributorRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingDistributorService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributorRepository $repository,
    ) {}

    public function getRepository(): MarketingDistributorRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
