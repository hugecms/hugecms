<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingCommissionRuleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingCommissionRuleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingCommissionRuleRepository $repository,
    ) {}

    public function getRepository(): MarketingCommissionRuleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
