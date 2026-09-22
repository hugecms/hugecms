<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StatisticsDailyRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class StatisticsDailyService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly StatisticsDailyRepository $repository,
    ) {}

    public function getRepository(): StatisticsDailyRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
