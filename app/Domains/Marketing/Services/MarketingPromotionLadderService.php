<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingPromotionLadderRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingPromotionLadderService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingPromotionLadderRepository $repository,
    ) {}

    public function getRepository(): MarketingPromotionLadderRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
