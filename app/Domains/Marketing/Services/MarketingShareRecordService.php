<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingShareRecordRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingShareRecordService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingShareRecordRepository $repository,
    ) {}

    public function getRepository(): MarketingShareRecordRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
