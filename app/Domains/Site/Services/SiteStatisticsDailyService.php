<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteStatisticsDailyRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteStatisticsDailyService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteStatisticsDailyRepository $repository,
    ) {}

    public function getRepository(): SiteStatisticsDailyRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
