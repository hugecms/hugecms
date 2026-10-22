<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteDailyStatisticRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteDailyStatisticService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteDailyStatisticRepository $repository,
    ) {}

    public function getRepository(): SiteDailyStatisticRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
