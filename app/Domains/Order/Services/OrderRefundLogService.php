<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderRefundLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderRefundLogService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderRefundLogRepository $repository,
    ) {}

    public function getRepository(): OrderRefundLogRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
