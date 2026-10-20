<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderRefundItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderRefundItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderRefundItemRepository $repository,
    ) {}

    public function getRepository(): OrderRefundItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
