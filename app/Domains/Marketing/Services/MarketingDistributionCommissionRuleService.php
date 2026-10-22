<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionCommissionRuleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingDistributionCommissionRuleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingDistributionCommissionRuleRepository $repository,
    ) {}

    public function getRepository(): MarketingDistributionCommissionRuleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
