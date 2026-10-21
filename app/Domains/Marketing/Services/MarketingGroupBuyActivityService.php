<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingGroupBuyActivityRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingGroupBuyActivityService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingGroupBuyActivityRepository $repository,
    ) {}

    public function getRepository(): MarketingGroupBuyActivityRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
