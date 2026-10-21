<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemRiskRecordRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemRiskRecordService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemRiskRecordRepository $repository,
    ) {}

    public function getRepository(): SystemRiskRecordRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
