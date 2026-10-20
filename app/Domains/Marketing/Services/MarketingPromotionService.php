<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingPromotionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingPromotionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingPromotionRepository $repository,
    ) {}

    public function getRepository(): MarketingPromotionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
