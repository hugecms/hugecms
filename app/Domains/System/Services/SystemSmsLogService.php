<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemSmsLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemSmsLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemSmsLogRepository $repository,
    ) {}

    public function getRepository(): SystemSmsLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
