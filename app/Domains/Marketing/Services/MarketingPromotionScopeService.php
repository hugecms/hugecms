<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingPromotionScopeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingPromotionScopeService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingPromotionScopeRepository $repository,
    ) {}

    public function getRepository(): MarketingPromotionScopeRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
