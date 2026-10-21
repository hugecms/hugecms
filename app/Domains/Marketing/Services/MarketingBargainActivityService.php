<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingBargainActivityRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingBargainActivityService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingBargainActivityRepository $repository,
    ) {}

    public function getRepository(): MarketingBargainActivityRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
