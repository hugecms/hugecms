<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingCouponRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingCouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingCouponRepository $repository,
    ) {}

    public function getRepository(): MarketingCouponRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
