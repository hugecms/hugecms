<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingUserCouponRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingUserCouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingUserCouponRepository $repository,
    ) {}

    public function getRepository(): MarketingUserCouponRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
