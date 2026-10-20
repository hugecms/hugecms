<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemOperationLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemOperationLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemOperationLogRepository $repository,
    ) {}

    public function getRepository(): SystemOperationLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
