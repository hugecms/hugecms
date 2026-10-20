<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemLoginLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemLoginLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemLoginLogRepository $repository,
    ) {}

    public function getRepository(): SystemLoginLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
