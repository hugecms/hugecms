<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingGroupBuyTeamService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingGroupBuyTeamRepository $repository,
    ) {}

    public function getRepository(): MarketingGroupBuyTeamRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
