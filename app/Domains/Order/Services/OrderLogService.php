<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderLogRepository $repository,
    ) {}

    public function getRepository(): OrderLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
