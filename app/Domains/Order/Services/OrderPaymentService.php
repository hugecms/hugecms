<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderPaymentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderPaymentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderPaymentRepository $repository,
    ) {}

    public function getRepository(): OrderPaymentRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
