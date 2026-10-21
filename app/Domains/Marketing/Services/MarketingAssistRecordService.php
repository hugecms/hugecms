<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingAssistRecordRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingAssistRecordService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingAssistRecordRepository $repository,
    ) {}

    public function getRepository(): MarketingAssistRecordRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
