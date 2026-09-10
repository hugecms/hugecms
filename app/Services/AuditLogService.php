<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuditLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AuditLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AuditLogRepository $repository,
    ) {}

    public function getRepository(): AuditLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
